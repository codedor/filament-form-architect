@php
    $state = $getState();
    $statePath = $getStatePath();
    $locales = $getLocales();
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div class="flex flex-col gap-2">
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
                    <div class="grow flex flex-col gap-2">
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
                                    <div
                                        class="w-full flex gap-2 items-center"
                                        style="grid-column: span {{ $block['width'] ?? 12 }};"
                                        x-sortable-item="{{ $uuid }}"
                                        :key="$uuid"
                                    >
                                        <div class="
                                            relative grow bg-gray-50 p-4 rounded-lg
                                            border justify-between flex gap-2
                                            group
                                        ">
                                            <div class="flex flex-col text-sm">
                                                <div class="flex gap-1">
                                                    <strong>
                                                        {{ $block['data']['working_title'] ?? 'New field' }}
                                                    </strong>

                                                    @foreach ($locales as $locale)
                                                        <x-filament-form-architect::locale-indicator
                                                            :online="$block['data'][$locale]['online'] ?? false"
                                                            :locale="$locale"
                                                        />
                                                    @endforeach
                                                </div>

                                                <span class="text-xs">
                                                    {{ $block['type']::make()->getName() }}
                                                </span>
                                            </div>

                                            <div class="
                                                absolute top-2 right-2 flex gap-1
                                                opacity-0 group-hover:opacity-100
                                            ">
                                                @if (count($row) > 1)
                                                    <x-filament::icon-button
                                                        color="gray"
                                                        icon="heroicon-o-arrows-right-left"
                                                        class="border-2 bg-white"
                                                        size="sm"
                                                        icon-size="sm"
                                                        x-sortable-handle
                                                    />
                                                @endif

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
