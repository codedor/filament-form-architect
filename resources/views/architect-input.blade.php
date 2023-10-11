@php
    $state = $getState();
    $statePath = $getStatePath();
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div class="flex flex-col gap-2">
        <div class="flex w-full justify-center ml-4">
            <x-filament-form-architect::icon-button
                :action="$getAction('addBlock')"
                :state-path="$getStatePath()"
                :arguments="['row' => -1]"
            />
        </div>

        <div
            x-sortable
            x-on:end.stop="$wire.dispatch('{{ $statePath }}', 'reorder', { items: $event.target.sortable.toArray() })"
        >
            @foreach ($state ?? [] as $rowKey => $row)
                <div
                    class="w-full flex gap-2 bg-white p-2 items-center"
                    x-sortable-item="{{ $rowKey }}"
                >
                    <x-heroicon-o-arrows-up-down
                        class="w-6 h-6 text-gray-400 cursor-grab -mt-10"
                        x-sortable-handle
                    />

                    <div class="grow flex flex-col gap-2">
                        <div
                            x-sortable
                            class="grow flex gap-2 items-center"
                        >
                            <x-filament-form-architect::icon-button
                                :action="$getAction('addBlockBetween')"
                                :state-path="$getStatePath()"
                                :arguments="['row' => $rowKey, 'insertAfter' => 0]"
                            />

                            @foreach ($row as $uuid => $block)
                                <div
                                    class="grow flex gap-2 items-center"
                                    x-sortable-item="{{ $uuid }}"
                                    x-sortable-handle
                                    :key="$uuid"
                                >
                                    <div class="grow bg-gray-50 p-4 rounded-lg border justify-between flex gap-2">
                                        <div class="flex flex-col text-sm">
                                            <strong>
                                                {{ $block['data']['working_title'] ?? 'New field' }}
                                            </strong>
                                            <span class="text-xs">
                                                {{ $block['type']::make()->getName() }}
                                            </span>
                                        </div>

                                        <div class="flex gap-1">
                                            <x-filament-form-architect::icon-button
                                                :action="$getAction('editBlock')"
                                                :state-path="$getStatePath()"
                                                :arguments="[
                                                    'uuid' => $uuid,
                                                    'row' => $rowKey,
                                                    'block' => $block,
                                                ]"
                                            />

                                            <x-filament-form-architect::icon-button
                                                color="danger"
                                                :action="$getAction('deleteBlock')"
                                                :state-path="$getStatePath()"
                                                :arguments="[
                                                    'uuid' => $uuid,
                                                    'row' => $rowKey,
                                                ]"
                                            />
                                        </div>
                                    </div>

                                    <x-filament-form-architect::icon-button
                                        :action="$getAction('addBlockBetween')"
                                        :state-path="$getStatePath()"
                                        :arguments="['row' => $rowKey, 'insertAfter' => $uuid]"
                                    />
                                </div>
                            @endforeach
                        </div>

                        <div class="flex w-full justify-center">
                            <x-filament-form-architect::icon-button
                                :action="$getAction('addBlock')"
                                :state-path="$getStatePath()"
                                :arguments="['row' => $rowKey]"
                            />
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-dynamic-component>
