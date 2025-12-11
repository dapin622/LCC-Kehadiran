<?php

namespace App\Exports;

use App\Models\School;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SchoolExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return School::select('id', 'name', 'region', 'province')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Sekolah',
            'Region',
            'Province',
        ];
    }
}
