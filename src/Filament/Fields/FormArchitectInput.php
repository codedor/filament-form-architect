<?php

namespace Codedor\FormArchitect\Filament\Fields;

use Closure;
use Codedor\FormArchitect\Facades\BlockCollection;
use Codedor\LocaleCollection\Facades\LocaleCollection;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Select;
use Filament\Support\Enums\ActionSize;
use Illuminate\Support\Str;

class FormArchitectInput extends Field
{
    protected string $view = 'filament-form-architect::architect-input';

    public null|Closure|iterable $blocks = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->default([]);

        $this->registerActions([
            fn (self $component): Action => $component->getAddBlockAction(),
            fn (self $component): Action => $component->getAddBlockBetweenAction(),
            fn (self $component): Action => $component->getEditBlockAction(),
            fn (self $component): Action => $component->getDeleteBlockAction(),
        ]);

        $this->registerListeners([
            'filament-form-architect::editedBlock' => [
                function (self $component, string $statePath, array $arguments): void {
                    if ($statePath !== $component->getStatePath()) {
                        return;
                    }

                    $items = $component->getState();
                    $items[$arguments['row']][$arguments['uuid']]['data'] = $arguments['form']['state'];
                    $component->state($items);
                },
            ],
            'reorder-row' => [
                function (self $component, string $statePath, array $data): void {
                    if ($statePath !== $component->getStatePath()) {
                        return;
                    }

                    $items = $component->getState();

                    $items = collect($items)
                        ->sortBy(fn ($item, $key) => array_search($key, $data['newKeys']))
                        ->values()
                        ->toArray();

                    $component->state($items);
                },
            ],
            'reorder-column' => [
                function (self $component, string $statePath, array $data): void {
                    if ($statePath !== $component->getStatePath()) {
                        return;
                    }

                    $items = $component->getState();

                    $items[$data['row']] = collect($items[$data['row']])
                        ->sortBy(fn ($item, $key) => array_search($key, $data['newKeys']))
                        ->toArray();

                    $component->state($items);
                },
            ],
        ]);
    }

    public function getDeleteBlockAction(): Action
    {
        return Action::make('deleteBlock')
            ->icon('heroicon-o-trash')
            ->hiddenLabel()
            ->color('danger')
            ->size(ActionSize::Small)
            ->closeModalByClickingAway(false)
            ->requiresConfirmation()
            ->action(function (self $component, array $arguments) {
                $items = $component->getState();

                unset($items[$arguments['row']][$arguments['uuid']]);

                if (empty($items[$arguments['row']])) {
                    unset($items[$arguments['row']]);
                } else {
                    $items = $this->normalizeWidth($items);
                }

                $component->state(array_values($items));
            });
    }

    public function getEditBlockAction(): Action
    {
        return Action::make('editBlock')
            ->icon('heroicon-o-pencil')
            ->hiddenLabel()
            ->color('gray')
            ->size(ActionSize::Small)
            ->closeModalByClickingAway(false)
            ->modalSubmitAction(false)
            ->modalCancelAction(false)
            ->modalContent(static fn (self $component, array $arguments) => view(
                'filament-form-architect::edit-modal',
                [
                    'arguments' => $arguments,
                    'statePath' => $component->getStatePath(),
                ]
            ));
    }

    public function getBaseAddBlockAction(string $name): Action
    {
        return Action::make($name)
            ->icon('heroicon-o-plus')
            ->hiddenLabel()
            ->color('gray')
            ->size(ActionSize::Small)
            ->closeModalByClickingAway(false)
            ->form(fn () => [
                Select::make('block')
                    ->options(fn () => collect($this->getBlocks())->map(fn ($b) => $b->getName()))
                    ->hiddenLabel()
                    ->required(),
            ]);
    }

    public function getAddBlockAction(): Action
    {
        return $this->getBaseAddBlockAction('addBlock')->action(function (self $component, array $arguments, array $data) {
            $newUuid = (string) Str::uuid();

            $items = $component->getState();
            $newBlock = $this->newBlock($data);

            // If the state is empty, add the new block to the start of the array
            if (empty($items)) {
                $items = [[$newUuid => $newBlock]];
                $component->state($items);

                return;
            }

            // Insert between the current row and the next one
            $items = array_merge(
                array_slice($items, 0, $arguments['row'] + 1),
                [[$newUuid => $newBlock]],
                array_slice($items, $arguments['row'] + 1),
            );

            $component->state($items);
        });
    }

    public function getAddBlockBetweenAction(): Action
    {
        return $this->getBaseAddBlockAction('addBlockBetween')->action(function (self $component, array $arguments, array $data) {
            $newUuid = (string) Str::uuid();

            $after = $arguments['insertAfter'] ?? null;
            $newBlock = $this->newBlock($data);
            $newBlock['width'] = 12;

            // Insert between the current column and the next one
            if ($after) {
                $items = [];
                foreach ($component->getState() as $rowKey => $row) {
                    foreach ($row as $uuid => $item) {
                        $items[$rowKey][$uuid] = $item;

                        if ($uuid === $after) {
                            $items[$rowKey][$newUuid] = $newBlock;
                        }
                    }
                }
            } else {
                // Add the new block to the start of the row array
                $items = $component->getState();

                $items[$arguments['row']] = array_merge(
                    [$newUuid => $newBlock],
                    $items[$arguments['row']],
                );
            }

            $items = $this->normalizeWidth($items);

            $component->state($items);
        });
    }

    public function blocks(null|Closure|iterable $blocks): static
    {
        $this->blocks = $blocks;

        return $this;
    }

    public function getBlocks(): iterable
    {
        return $this->evaluate($this->blocks)
            ?? BlockCollection::fromConfig();
    }

    public function getLocales(): array
    {
        return LocaleCollection::map(fn ($locale) => $locale->locale())->toArray();
    }

    private function newBlock(array $data)
    {
        return [
            'type' => get_class($this->getBlocks()[$data['block']]),
            'width' => 12,
            'data' => [],
        ];
    }

    private function normalizeWidth(array $items)
    {
        foreach ($items as $key => $row) {
            $totalWidth = collect($row)->sum('width');

            if ($totalWidth === 12) {
                continue;
            }

            foreach (array_keys($row) as $uuid) {
                $items[$key][$uuid]['width'] = 12 / count($row);
            }
        }

        return $items;
    }
}
