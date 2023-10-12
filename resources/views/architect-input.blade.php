@php
    $state = $getState();
    $statePath = $getStatePath();
    $locales = $getLocales();
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div class="flex flex-col gap-4">
        <div class="flex w-full justify-center">
            <x-filament-form-architect::icon-button
                :action="$getAction('addBlock')"
                :state-path="$getStatePath()"
                :arguments="['row' => -1]"
            />
        </div>

        <div
            x-sortable
            x-on:end.stop="$wire.dispatchFormEvent('reorder-row', '{{ $statePath }}', {
                newKeys: $event.target.sortable.toArray(),
            })"
        >
            @foreach ($state ?? [] as $rowKey => $row)
                <div
                    class="w-full flex gap-2 bg-white p-2 items-center"
                    x-sortable-item="{{ $rowKey }}"
                >
                    <div class="grow flex flex-col gap-4">
                        <div class="grow flex gap-2 items-center">
                            <div class="flex flex-col gap-2">
                                <x-filament-form-architect::icon-button
                                    :action="$getAction('addBlockBetween')"
                                    :state-path="$getStatePath()"
                                    :arguments="['row' => $rowKey, 'insertAfter' => 0]"
                                />

                                @if (count($state) > 1)
                                    <x-filament::icon-button
                                        color="gray"
                                        icon="heroicon-o-arrows-up-down"
                                        class="border-2 bg-white"
                                        size="sm"
                                        icon-size="sm"
                                        x-sortable-handle
                                    />
                                @endif
                            </div>

                            <div
                                class="grow w-full grid gap-2 grid-cols-12"
                                x-sortable
                                x-on:end.stop="$wire.dispatchFormEvent('reorder-column', '{{ $statePath }}', {
                                    newKeys: $event.target.sortable.toArray(),
                                    row: '{{ $rowKey }}',
                                })"
                            >
                                @foreach ($row as $uuid => $block)
                                    <x-filament-form-architect::input-row
                                        :uuid="$uuid"
                                        :row="$row"
                                        :row-key="$rowKey"
                                        :block="$block"
                                        :locales="$locales"
                                        :state-path="$statePath"
                                        :get-action="$getAction"
                                        :loop="$loop"
                                    />
                                @endforeach
                            </div>
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
