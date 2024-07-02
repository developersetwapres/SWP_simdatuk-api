<?php

namespace App\Http\Controllers;

use App\Exports\ComparisonsExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

/**
 * @group Export
 */
class ExportComparisonController extends Controller
{
    public $users, $positions, $strukturals, $fungsionals, $tekniss, $targets, $disciplinaries, $notes, $assessments, $competencies, $talents;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->posted = $request->except('_token', '_method');
    }

    /**
     * Export Comparison
     *
     * Export comparison data between employee
     * @group Export
     * @authenticated
     * @queryParam ids string Refers to list of id users. Example: 244, 327
     * @queryParam output string Refers output of data, the list is .pdf, .xlsx, .csv. Example: .pdf
     */
    public function index()
    {
        $messages = [
            'ids.required' => 'Ids tidak boleh kosong.',
            'output.required' => 'Output tidak boleh kosong.',
            'output.in' => 'Output harus diantara .pdf, .xlsx, .csv.',
        ];

        $validatedData = $this->request->validate([
            'ids' => 'required',
            'output' => 'required|in:.pdf,.xlsx,.csv',
        ], $messages);

        $ids = explode(',', $this->request->ids);
        $ids = array_map('intval', $ids);
        $this->users = $this->getUsers($ids);

        $ids = array();
        foreach ($this->users as $item) {
            array_push($ids, $item->id);
        }

        // Collect data
        $this->positions = $this->getPositions($ids);
        $this->strukturals = $this->getTrainings($ids, 1);
        $this->fungsionals = $this->getTrainings($ids, 2);
        $this->tekniss = $this->getTrainings($ids, 3);
        $this->targets = $this->getTargets($ids);
        $this->disciplinaries = $this->getDisciplinaries($ids);
        $this->notes = $this->getNotes($ids);
        $this->assessments = $this->getAssesments($ids);
        $this->competencies = $this->getCompetencies($ids);
        $this->talents = $this->getTalents($ids);

        $title = 'Bandingkan Pegawai';
        $date = Carbon::now()->timezone('Asia/Jakarta')->locale('id')->isoFormat('D MMMM Y');

        if ($this->request->output == '.pdf') {
            return $this->downloadPdf($title, $date);
        } elseif ($this->request->output == '.xlsx') {
            return (new ComparisonsExport(
                $this->users,
                $this->positions,
                $this->strukturals,
                $this->fungsionals,
                $this->tekniss,
                $this->targets,
                $this->disciplinaries,
                $this->notes,
                $this->assessments,
                $this->competencies,
                $this->talents,
            ))->download($title . ' - ' . $date . '.xlsx');
        } else {
            return (new ComparisonsExport(
                $this->users,
                $this->positions,
                $this->strukturals,
                $this->fungsionals,
                $this->tekniss,
                $this->targets,
                $this->disciplinaries,
                $this->notes,
                $this->assessments,
                $this->competencies,
                $this->talents,
            ))->download($title . ' - ' . $date . '.csv');
        }
    }

    private function downloadPdf($title, $date)
    {
        $tmp = sys_get_temp_dir();
        $pdf = Pdf::loadview('exports/comparison', [
            'title' => $title,
            'date' => $date,
            'data' => [
                'users' => $this->users,
                'positions' => $this->positions,
                'strukturals' => $this->strukturals,
                'fungsionals' => $this->fungsionals,
                'tekniss' => $this->tekniss,
                'targets' => $this->targets,
                'disciplinaries' => $this->disciplinaries,
                'notes' => $this->notes,
                'assessments' => $this->assessments,
                'competencies' => $this->competencies,
                'talents' => $this->talents,
            ],
        ]);
        $pdf->set_option('isHtml5ParserEnabled', true);
        $pdf->set_paper("A2", "landscape");
        $pdf->set_option('isRemoteEnabled', true);
        $pdf->set_option('fontDir', $tmp);
        $pdf->set_option('fontCache', $tmp);
        $pdf->set_option('tempDir', $tmp);
        return $pdf->download($title . ' - ' . $date . '.pdf');
    }

    private function getUsers($ids)
    {
        $users = DB::table('users as u');
        $users->leftJoin('positions as p', 'u.position_id', '=', 'p.id');
        $users->leftJoin('echelons as e', 'u.echelon_id', '=', 'e.id');
        $users->leftJoin('grades as g', 'u.grade_id', '=', 'g.id');
        $users->select(
            'u.id',
            DB::raw("
                CASE
                    WHEN u.title_prefix IS NULL && u.title_suffix IS NULL THEN u.name
                    WHEN u.title_prefix IS NOT NULL && u.title_suffix IS NULL THEN CONCAT(u.title_prefix, ' ', u.name)
                    WHEN u.title_prefix IS NULL && u.title_suffix IS NOT NULL THEN CONCAT(u.name, ' ', u.title_suffix)
                    ELSE CONCAT(u.title_prefix, ' ',u.name, ' ',u.title_suffix)
                END AS name
            "),
            'u.photo_profile',
            'p.name as position_name',
            'e.name as echelon_name',
            DB::raw("DATE_FORMAT(u.echelon_effective_date, '%d-%m-%Y') as echelon_effective_date"),
            DB::raw("CONCAT(g.name, ' ', g.code) as grade_name"),
            DB::raw("DATE_FORMAT(u.grade_effective_date, '%d-%m-%Y') as grade_effective_date"),
            'u.education_level',
            'u.education_name',
        );
        $users->whereIn('u.id', $ids);
        $users->orderBy('u.echelon_id', 'asc');
        $users->orderBy('u.grade_id', 'asc');
        $users = $users->get();
        foreach ($users as $item) {
            $item->photo_profile = $this->getDocument($item->photo_profile, true);

            $educationLevel = [
                1 => 'SD/Sederajat',
                2 => 'SLTP/Sederajat',
                3 => 'SLTA/Sederajat',
                4 => 'Diploma I/II',
                5 => 'Akademik/D3/S.Muda',
                6 => 'Diploma IV/Strata I',
                7 => 'Strata II',
                8 => 'Strata III',
            ];

            $item->education_level = $educationLevel[$item->education_level] ?? '';
        }
        return $users;
    }

    private function getPositions($ids)
    {
        $positions = DB::table('users as u');
        $positions->select('u.id', 'phu.position');
        $positions->join('position_history_users as phu', 'u.id', '=', 'phu.user_id');
        $positions->whereIn('u.id', $ids);
        $positions = $positions->get();
        return $this->groupingData($ids, $positions);
    }

    private function getTrainings($ids, $type)
    {
        $trainings = DB::table('users as u');
        $trainings->select('u.id', 'th.name');
        $trainings->join('training_history_users as thu', 'u.id', '=', 'thu.user_id');
        $trainings->join('training_histories as th', 'thu.training_history_id', '=', 'th.id');
        $trainings->whereIn('u.id', $ids);
        $trainings->where('th.type', $type);
        $trainings = $trainings->get();
        return $this->groupingData($ids, $trainings);
    }

    private function getTargets($ids)
    {
        $targets = DB::table('users as u');
        $targets->select('u.id', 'thu.work_behavior_rating', 'thu.employee_performance_predicate', 'thu.organizational_performance_achievement');
        $targets->join('target_history_users as thu', 'u.id', '=', 'thu.user_id');
        $targets->whereIn('u.id', $ids);
        $targets = $targets->get();
        foreach ($targets as $target) {
            $target->work_behavior_rating = $target->work_behavior_rating == 1 ? 'Diatas Ekspektasi' : ($target->work_behavior_rating == 2 ? 'Sesuai Ekspektasi' : ($target->work_behavior_rating == 3 ? 'Dibawah Ekspektasi' : ''));

            $performanceMapping = [
                1 => 'Sangat Baik',
                2 => 'Baik',
                3 => 'Butuh Perbaikan',
                4 => 'Kurang',
                5 => 'Sangat Kurang',
            ];

            $target->employee_performance_predicate = $performanceMapping[$target->employee_performance_predicate] ?? '';
        }
        return $this->groupingData($ids, $targets);
    }

    private function getDisciplinaries($ids)
    {
        $disciplinaries = DB::table('users as u');
        $disciplinaries->select(
            'u.id',
            'd.description',
            DB::raw("DATE_FORMAT(dhu.start_date, '%d-%m-%Y') as start_date")
        );
        $disciplinaries->join('disciplinary_history_users as dhu', 'u.id', '=', 'dhu.user_id');
        $disciplinaries->join('disciplinaries as d', 'dhu.disciplinary_id', '=', 'd.id');
        $disciplinaries->whereIn('u.id', $ids);
        $disciplinaries = $disciplinaries->get();
        return $this->groupingData($ids, $disciplinaries);
    }

    private function getNotes($ids)
    {
        $notes = DB::table('users as u');
        $notes->select('u.id', 'un.description');
        $notes->join('user_notes as un', 'u.id', '=', 'un.user_id');
        $notes->whereIn('u.id', $ids);
        $notes = $notes->get();
        return $this->groupingData($ids, $notes);
    }

    private function getAssesments($ids)
    {
        $assessments = DB::table('users as u');
        $assessments->select(
            'u.id',
            DB::raw("DATE_FORMAT(ua.event_date, '%d-%m-%Y') as event_date"),
            'ua.point'
        );
        $assessments->join('user_assessments as ua', 'u.id', '=', 'ua.user_id');
        $assessments->whereIn('u.id', $ids);
        $assessments = $assessments->get();
        foreach ($assessments as $assessment) {
            $assessment->point = $assessment->point == 1 ? 'Kurang Memenuhi Syarat' : ($assessment->point == 2 ? 'Masih Memenuhi Syarat' : ($assessment->point == 3 ? 'Memenuhi Syarat' : ''));
        }
        return $this->groupingData($ids, $assessments);
    }

    private function getCompetencies($ids)
    {
        $competencies = DB::table('users as u');
        $competencies->select(
            'u.id',
            DB::raw("DATE_FORMAT(uc.event_date, '%d-%m-%Y') as event_date"),
            'uc.point'
        );
        $competencies->join('user_competencies as uc', 'u.id', '=', 'uc.user_id');
        $competencies->whereIn('u.id', $ids);
        $competencies = $competencies->get();
        foreach ($competencies as $competency) {
            $competency->point = $competency->point == 1 ? 'Lulus' : (($competency->point == 2) ? 'Tidak Lulus' : '');
        }
        return $this->groupingData($ids, $competencies);
    }

    private function getTalents($ids)
    {
        $talents = DB::table('users as u');
        $talents->select(
            'u.id',
            DB::raw("DATE_FORMAT(ut.event_date, '%d-%m-%Y') as event_date"),
            'ut.point'
        );
        $talents->join('user_talents as ut', 'u.id', '=', 'ut.user_id');
        $talents->whereIn('u.id', $ids);
        $talents = $talents->get();
        return $this->groupingData($ids, $talents);
    }

    private function groupingData($ids, $data)
    {
        // Initialize the grouped data array with empty arrays for each ID
        foreach ($ids as $id) {
            $groupedData[$id] = [];
        }

        // Group the data based on IDs
        foreach (json_decode(json_encode($data), true) as $item) {
            if (in_array($item['id'], $ids)) {
                $groupedData[$item['id']][] = $item;
            }
        }
        return $groupedData;
    }
}
