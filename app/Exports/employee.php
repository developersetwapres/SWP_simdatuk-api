<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class employee implements FromView, WithDrawings, WithEvents
{
    private $id;
    private array $toggleField;

    public function __construct(int $id, array $toggleField)
    {
        $this->id = $id;
        $this->toggleField = $toggleField;
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
                $alphabet       = $event->sheet->getHighestDataColumn();
                $totalRow       = $event->sheet->getHighestDataRow();
                $cellRange      = 'A3:'.$alphabet.$totalRow;
                $event->sheet->getStyle($cellRange)->getAlignment()->setWrapText(true);
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
                $desiredWidth = 30; // Example width, adjust as needed
                for ($col = 2; $col <= $lastColumnIndex; $col++) {
                    $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
                    $event->sheet->getColumnDimension($columnLetter)->setWidth($desiredWidth);

                    // Apply text alignment to top center
                    $event->sheet->getStyle($columnLetter . '2:' . $columnLetter . $totalRow)
                        ->getAlignment()->setVertical(Alignment::HORIZONTAL_CENTER)
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER);
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
        $usersId = [3];
        $users = DB::table('users');
        $this->toggleField['isName'] = true;
        $this->toggleField['isPosition'] = true;
        $this->toggleField['isPositionDescription'] = false;
        $this->toggleField['isEchelons'] = true;
        $this->toggleField['isGrade'] = true;
        $this->toggleField['isNip'] = true;
        $this->toggleField['isBirthPlaceDate'] = true;
        $this->toggleField['isAge'] = false;
        $this->toggleField['isReligion'] = true;
        $this->toggleField['isGender'] = true;
        $this->toggleField['isMaritalStatus'] = true;
        $this->toggleField['isAgency'] = true;
        $this->toggleField['isOrganization'] = true;
        $this->toggleField['isWorkUnit'] = false;
        $this->toggleField['isNoWorker'] = true;
        $this->toggleField['workDuration'] = false;
        $this->toggleField['isGradeDuration'] = true;
        $this->toggleField['isNPWP'] = true;
        $this->toggleField['isEmployeeStatus'] = false;
        $this->toggleField['isCurrentAddress'] = true;
        $this->toggleField['isComplex'] = true;
        $this->toggleField['isHomeNumber'] = true;
        $this->toggleField['isPhoneNumber'] = true;
        $this->toggleField['isOfficeAddress'] = true;
        $this->toggleField['isOfficeNumber'] = true;
        $this->toggleField['isEmail'] = true;
        $this->toggleField['isPensionCap'] = false;
        $this->toggleField['isPositionHistory'] = true;
        $this->toggleField['isGradeHistory'] = true;
        $this->toggleField['isTrainingStructural'] = true;
        $this->toggleField['isTrainingFunctional'] = true;
        $this->toggleField['isTrainingTechnique'] = true;
        $this->toggleField['isSKP'] = false;

        if ($this->toggleField['isName']) {
            $users->addSelect('users.name');
        }
        if ($this->toggleField['isPosition']){
            $users->leftJoin('positions', 'users.position_id', '=', 'positions.id' );
            $users->addSelect('positions.name as position_name');
        }
//        if ($this->toggleField['isPositionDescription']){
//            //
//        }
        if (isset($this->toggleField['isEchelons'])){
            $users->leftJoin('echelons', 'users.echelon_id', '=', 'echelons.id');
            $users->addSelect('echelons.name as echelons_name');
        }
        if (isset($this->toggleField['isGrade'])){
            $users->leftJoin('grades as g', 'users.grade_id', '=', 'g.id');
            $users->addSelect('g.name as grade_name');
        }
        if (isset($this->toggleField['isNip'])){
            $users->addSelect('users.id_number');
        }
        if (isset($this->toggleField['isBirthPlaceDate'])){
            $users->addSelect('users.place_of_birth', 'users.date_of_birth');
        }
        if (isset($this->toggleField['isReligion'])){
            $users->addSelect('users.religion');
        }
        if (isset($this->toggleField['isGender'])){
            $users->addSelect('users.gender');
        }
        if (isset($this->toggleField['isMaritalStatus'])){
            $users->addSelect('users.marital_status');
        }
        if (isset($this->toggleField['isAgency'])){
            $users->leftJoin('institutions as i', 'users.institution_id', '=', 'i.id');
            $users->addSelect('i.name as institution_name');
        }
        if (isset($this->toggleField['isOrganization'])) {
            $users->leftJoin('groups as o', 'users.organization_id', '=', 'o.id');
            $users->addSelect('o.name as organization_name');
        }
        // add unit work later
        if (isset($this->toggleField['isNoWorker'])){
            $users->addSelect('users.employee_id_number', 'users.employee_id_card_number');
        }
        //add full work duration later
        if (isset($this->toggleField['isGradeDuration'])){
            $users->addSelect(['users.grade_effective_date']);
        }
        if (isset($this->toggleField['isNPWP'])){
            $users->addSelect('users.id_tax');
        }
        if (isset($this->toggleField['isCurrentAddress'])){
            $users->addSelect('users.current_address');
        }
        if (isset($this->toggleField['isComplex'])){
            $users->leftJoin('residences as r', 'users.residence_id', '=', 'r.id');
            $users->addSelect('r.name as residence_name');
        }
        if (isset($this->toggleField['isHomeNumber'])){
            $users->addSelect('users.home_phone_number');
        }
        if (isset($this->toggleField['isPhoneNumber'])){
            $users->addSelect('users.mobile_phone');
        }
        if (isset($this->toggleField['isOfficeAddress'])){
            $users->addSelect('users.office_address');
        }
        if (isset($this->toggleField['isOfficeNumber'])){
            $users->addSelect('users.office_phone_number');
        }
        if (isset($this->toggleField['isEmail'])){
            $users->addSelect('users.email');
        }
        if (isset($this->toggleField['isPositionHistory'])){
            $gradeHistorySubquery = DB::table('grade_history_users as ghu');
            $gradeHistorySubquery->join('grades', 'grades.id', '=', 'ghu.grade_id');
            $gradeHistorySubquery->select('ghu.user_id', DB::raw("GROUP_CONCAT(CONCAT(grades.name, grades.code,
            ' (', ghu.decree_date, ',', ghu.decree_number, ')') SEPARATOR ' ') as grade_history"));
            $gradeHistorySubquery->whereIn('ghu.user_id', $usersId);
            $gradeHistorySubquery->groupBy('ghu.user_id');
            $users->leftJoinSub($gradeHistorySubquery, 'grade_history', function ($join) {
                $join->on('users.id', '=', 'grade_history.user_id');
            });
            $users->addSelect('grade_history.grade_history');
        }
        if (isset($this->toggleField['isGradeHistory'])){
            $positionHistorySubquery = DB::table('position_history_users as phu');
            $positionHistorySubquery->select('phu.user_id', DB::raw("GROUP_CONCAT(CONCAT(phu.position, '
            (', phu.decree_date, ',', phu.decree_number, ')') SEPARATOR ' ') as position_history"));
            $positionHistorySubquery->whereIn('phu.user_id', $usersId);
            $positionHistorySubquery->groupBy('phu.user_id');
            $users->leftJoinSub($positionHistorySubquery, 'position_history', function ($join) {
                $join->on('users.id', '=', 'position_history.user_id');
            });
            $users->addSelect('position_history.position_history');
        }
        if (isset($this->toggleField['isTrainingStructural'])){
            $trainingStructuralSubquery = DB::table('trainings as t')
                ->join('user_trainings as ut', 't.id', '=', 'ut.training_id')
                ->select('ut.user_id', DB::raw("
            GROUP_CONCAT(
                CONCAT(
                    t.name, ' (Periode: ', t.period_month, ' ', t.period_year, ', Start Date: ', t.start_date, ') Level: ', t.level, ', Organizer: ', t.organizer
                ) SEPARATOR ' '
            ) as structural_training_history
        "))
                ->whereIn('ut.user_id', $usersId)
                ->where('t.type', 1)
                ->groupBy('ut.user_id');

            $users->leftJoinSub($trainingStructuralSubquery, 'structural_training_history', function ($join) {
                $join->on('users.id', '=', 'structural_training_history.user_id');
            });

            $users->addSelect('structural_training_history.structural_training_history');
        }

        if (isset($this->toggleField['isTrainingFunctional'])){
            $trainingFunctionalSubquery = DB::table('trainings as t')
                ->join('user_trainings as ut', 't.id', '=', 'ut.training_id')
                ->select('ut.user_id', DB::raw("
            GROUP_CONCAT(
                CONCAT(
                    t.name, ' (Periode: ', t.period_month, ' ', t.period_year, ', Start Date: ', t.start_date, ') Level: ', t.level, ', Organizer: ', t.organizer
                ) SEPARATOR ' '
            ) as functional_training_history
        "))
                ->whereIn('ut.user_id', $usersId)
                ->where('t.type', 2)
                ->groupBy('ut.user_id');

            $users->leftJoinSub($trainingFunctionalSubquery, 'functional_training_history', function ($join) {
                $join->on('users.id', '=', 'functional_training_history.user_id');
            });

            $users->addSelect('functional_training_history.functional_training_history');
        }

        if (isset($this->toggleField['isTrainingTechnique'])){
            $trainingTechnicSubquery = DB::table('trainings as t')
                ->join('user_trainings as ut', 't.id', '=', 'ut.training_id')
                ->select('ut.user_id', DB::raw("GROUP_CONCAT(CONCAT(t.name, ' (Periode: ', t.period_month, ' ',
                t.period_year, ', Start Date: ', t.start_date, ') Level: ', t.level, ', Organizer: ', t.organizer) SEPARATOR ' ') as technique_training_history"))
                ->whereIn('ut.user_id', $usersId)
                ->where('t.type', 3)
                ->groupBy('ut.user_id');

            $users->leftJoinSub($trainingTechnicSubquery, 'technique_training_history', function ($join) {
                $join->on('users.id', '=', 'technique_training_history.user_id');
            });

            $users->addSelect('technique_training_history.technique_training_history');
        }
        $users->where('users.id', 3);
        $users->groupBy('users.id');
        $users = $users->get();
        $usersData = $users->map(function($item) {
            return (array) $item;
        })->toArray();
        return view('exports.employee', [
            'userData' => $usersData,
            'toggleField' => $this->toggleField,
        ]);
    }
}
