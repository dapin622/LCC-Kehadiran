<?php

namespace App\Exports;

use App\Models\Member;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MembersExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Member::with('school', 'class', 'team')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama',
            'NISN',
            'Gender',
            'Sekolah',
            'Tim',
            'Kelas',
            'QR Code',
        ];
    }

    public function map($member): array
    {
        return [
            $member->id,
            $member->name,
            $member->nisn,
            $member->gender,
            $member->school->name ?? '-',
            $member->team->name ?? '-',
            $member->class->name ?? '-',
            $member->qr_code,
        ];
    }
}
