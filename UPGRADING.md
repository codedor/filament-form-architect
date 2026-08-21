# Upgrading

## From v1 to v2

v2 moves the package to the wotzebra organisation, targets Filament 4/5,
Laravel 12/13 and PHP 8.3+, and swaps the `codedor/*` dependencies for their
`wotz/*` successors.

- Install `wotz/filament-form-architect` instead of `codedor/filament-form-architect`.
- Replace all occurrences of the `Codedor\FormArchitect` namespace with the new
  `Wotz\FormArchitect` namespace.
- If your project references classes of the underlying `codedor/*` packages
  directly, replace their `Codedor\` namespace prefix with `Wotz\` as well
  (e.g. `Codedor\LivewireForms\...` becomes `Wotz\LivewireForms\...`).
- `awcodes/filament-tiptap-editor` has been replaced by Filament's native
  `RichEditor`. The `checkbox-tiptap-profile` config key has been renamed to
  `checkbox-toolbar-buttons` and now takes an array of RichEditor toolbar buttons
  (default: `['bold', 'italic', 'link']`).
- `guava/filament-drafts` was an unused dependency and has been dropped.
- The `Wotz\TranslatableTabs\Resources\Traits\HasTranslations` page trait no longer
  exists; wotz/filament-translatable-tabs v3 handles translations through the schema
  lifecycle. If your project used it on custom Create/Edit pages, delete the `use`
  statement.
