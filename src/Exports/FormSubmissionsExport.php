<?php

namespace Codedor\FormArchitect\Exports;

use Codedor\FormArchitect\Models\FormSubmission;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FormSubmissionsExport implements FromCollection, WithHeadings
{
    protected Collection $submissions;

    protected array $headings = [];

    private array $firstHeadings = [
        'ID',
        'Submitted at',
        'Locale',
    ];

    public function __construct(Collection $submissions)
    {
        $this->submissions = $submissions->map(function (FormSubmission $record) {
            return collect($record->toExcelExport())
                ->put('Submitted at', $record->created_at->format('Y-m-d H:i:s'))
                ->put('Locale', $record->locale)
                ->put('ID', $record->id);
        });
    }

    public function collection(): Collection
    {
        return $this->submissions->map(function (Collection $submission) {
            return collect($this->headings)->map(fn (string $heading) => $submission->get($heading));
        });
    }

    public function headings(): array
    {
        $this->headings = [
            ...$this->firstHeadings,
            ...$this->submissions
                ->mapWithKeys(fn ($r) => $r)
                ->keys()
                ->unique()
                ->reject(fn (string $key) => in_array($key, $this->firstHeadings))
                ->toArray(),
        ];

        return $this->headings;
    }
}
