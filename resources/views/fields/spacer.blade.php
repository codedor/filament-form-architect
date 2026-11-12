<div @class([$field->colClass ?? config('livewire-forms.defaults.colClass')]) aria-hidden="true">
    <div style="height: {{ max(0, (int) $field->height) }}px"></div>
</div>
