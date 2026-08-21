# Upgrading

## From v1 to v2

v2 targets Filament 4/5, Laravel 12/13 and PHP 8.3+, and moves from the `codedor/*`
packages to their `wotz/*` successors.

- Replace the `codedor/*` dependencies with their `wotz/*` successors. If your project
  references their classes directly, replace the `Codedor\` namespace prefix with
  `Wotz\` (e.g. `Codedor\LivewireForms\...` becomes `Wotz\LivewireForms\...`).
- `awcodes/filament-tiptap-editor` has been replaced by Filament's native
  `RichEditor`. The `checkbox-tiptap-profile` config key has been renamed to
  `checkbox-toolbar-buttons` and now takes an array of RichEditor toolbar buttons
  (default: `['bold', 'italic', 'link']`).
- `guava/filament-drafts` was an unused dependency and has been dropped.
- The `Wotz\TranslatableTabs\Resources\Traits\HasTranslations` page trait no longer
  exists; wotz/filament-translatable-tabs v3 handles translations through the schema
  lifecycle. If your project used it on custom Create/Edit pages, delete the `use`
  statement.
