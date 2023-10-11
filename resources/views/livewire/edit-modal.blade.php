<div x-data>
    {{ $this->form }}

    <div class="fi-modal-footer w-full pt-6">
        <div class="fi-modal-footer-actions gap-3 flex flex-wrap items-center">
            <x-filament::button x-on:click.prevent="$wire.$parent.dispatchFormEvent(
                'filament-form-architect::editedBlock',
                '{{ $statePath }}',
                {
                    row: '{{ $arguments['row'] }}',
                    uuid: '{{ $arguments['uuid'] }}',
                    form: await $wire.getFormData(),
                }
            ) && close()">
                Sumbit
            </x-filament::button>

            <x-filament::button x-on:click.prevent="close" color="gray">
                Cancel
            </x-filament::button>
        </div>
    </div>
</div>
