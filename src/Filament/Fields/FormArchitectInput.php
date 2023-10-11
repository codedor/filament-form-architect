<?php

namespace Codedor\FormArchitect\Filament\Fields;

use Closure;
use Codedor\FormArchitect\Facades\BlockCollection;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Select;
use Filament\Support\Enums\ActionSize;
use Illuminate\Support\Str;

class FormArchitectInput extends Field
{
    protected string $view = 'filament-form-architect::architect-input';

    public null|Closure|array $blocks = null;

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

            $component->state($items);
        });
    }

    public function blocks(null|Closure|array $blocks): static
    {
        $this->blocks = $blocks;

        return $this;
    }

    public function getBlocks(): array
    {
        return $this->evaluate($this->blocks)
            ?? BlockCollection::filamentBlocks();
    }

    private function newBlock(array $data)
    {
        return [
            'type' => get_class($this->getBlocks()[$data['block']]),
            'data' => [],
        ];
    }
}
