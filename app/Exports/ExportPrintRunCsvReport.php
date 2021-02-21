<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportPrintRunCsvReport implements FromCollection, WithHeadings
{
    /** @var \Illuminate\Support\Collection $printRunIdCards */
    private $printRunIdCards;

    public function __construct($printRunIdCards)
    {
        $this->printRunIdCards = $printRunIdCards;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->printRunIdCards->map(function ($printRunIdCard) {
            return [
                'id' => $printRunIdCard->id,
                'card_id' => $printRunIdCard->card_id,
                'full_name' => $printRunIdCard->full_name,
                'membership_number' => $printRunIdCard->membership_number,
                'member_since' => $printRunIdCard->member_since,
                'discipline_list' => $printRunIdCard->discipline_list,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Id',
            'Card Id',
            'Full Name',
            'Membership Number',
            'Member Since',
            'Disciplines',
        ];
    }
}
