<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class employee implements FromView, WithDrawings, WithEvents, ShouldAutoSize
{
    private $id;
    private bool $gender, $isPosition, $isSKP, $isFamily, $isGolongan, $isTraining, $isRecognition, $isLeaves,
        $isTrainingStructural, $isPPK, $isPunishment, $isNotes;

    public function __construct(int $id, bool $gender, $isNotes)
    {
        $this->id = $id;
        $this->gender = $gender;
        $this->isNotes = $isNotes;
    }

    public function columnFormats(): array
    {
        return [
            'F' => '#0',
        ];
    }

    public function  registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $event->getDelegate()->getRowDimension('1')->setRowHeight('20');
                $event->sheet->styleCells(
                    'A2:K2',
                    [
                        //Set border Style
                        'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE,
                            ],

                        ],

                        //Set font style
                        'font' => [
                            'name'      =>  'Inter',
                            'color' => ['rgb' => 'ffffff'],
                        ],

                        //Set background style
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'argb' => '394346',
                            ]
                        ],

                    ]
                );
                $lastColumn = $event->sheet->getHighestColumn();
                $lastColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($lastColumn);

                // Iterate through columns from L to the last column
                for ($col = 12; $col <= $lastColumnIndex; $col++) {
                    // Get the cell coordinate
                    $cellCoordinate = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . '2';

                    // Check if there's data in the cell
                    if (!empty($event->sheet->getCell($cellCoordinate)->getValue())) {
                        // Apply the same style as in row 2, columns A to K
                        $event->sheet->duplicateStyle(
                            $event->sheet->getStyle('A2:K2'),
                            $cellCoordinate
                        );
                    }
                }
            },
        ];
    }

    public function drawings()
    {
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('This is my logo');
        $drawing->setPath(public_path('img/setneg-logo.png'));
        $drawing->setWidthAndHeight( 200, 25);
        $drawing->setCoordinates('A1');
        return $drawing;
    }


    public function view(): View
    {
        $users = DB::table('users');
        $users->leftJoin('user_educations as ue' , 'users.id', '=', 'ue.user_id');
        $users->leftJoin('user_notes as un', 'users.id', '=', 'un.user_id');
        $users->select('users.name', 'ue.name as school', 'users.employee_id_number', 'users.place_of_birth',
            'users.date_of_birth', 'users.employee_registration_number');
        if ($this->isNotes) {
            $users->addSelect('un.description as notes');
        }
//        $users->where('users.id', $this->id);
        $users->where('users.gender', 'like', '%' . $this->gender . '%');
        $users = $users->get();
        $usersData = array();
        foreach ($users as $user) {
            $birthDate = Carbon::parse($user->date_of_birth);
            $now = Carbon::now();

            $years = $now->diffInYears($birthDate);
            $months = $now->copy()->subYears($years)->diffInMonths($birthDate);
            $days = $now->copy()->subYears($years)->subMonths($months)->diffInDays($birthDate);
            $userData = [
                'name' => $user->name,
                'school' => $user->school,
                'employee_id_number' => $user->employee_id_number,
                'place_of_birth' => $user->place_of_birth,
                'date_of_birth' => $user->date_of_birth,
                'employee_registration_number' => $user->employee_registration_number,
                'age' => sprintf('%d tahun %d bulan %d hari', $years, $months, $days),
            ];

            // Add 'notes' only if it exists and $this->isNotes is true
            if ($this->isNotes && isset($user->notes)) {
                $userData['notes'] = $user->notes;
            }

            // Add $userData to $usersData
            $usersData[] = $userData;

        }
        return view('exports.employee', [
            'userData' => $usersData,
            'isNote' => $this->isNotes
        ]);
    }
}
