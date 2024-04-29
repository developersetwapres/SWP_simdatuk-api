<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * @group Export
 *
 * APIs for user management
 */
class ExportController extends Controller
{

    protected $request;
    protected $posted;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->posted = $request->except('_token', '_method');
    }

    /**
     * Users
     * @group Export
     * @authenticated
     */
    public function user()
    {
        // return view('exports/user', [
        //     'pegawai' => [1, 2, 3, 4, 5, 1, 2, 3, 4, 5, 1, 2, 3, 4, 5, 1, 2, 3, 4, 5, 1, 2, 3, 4, 5, 1, 2, 3, 4, 5, 1, 2, 3, 4, 5, 1, 2, 3, 4, 5, 1, 2, 3, 4, 5, 1, 2, 3, 4, 5, 1, 2, 3, 4, 5, 1, 2, 3, 4, 5, 1, 2, 3, 4, 5, 1, 2, 3, 4, 5, 1, 2, 3, 4, 5, 1, 2, 3, 4, 5],
        //     'userProfile' => [
        //         'Tempat, tanggal lahir' => 'Lorem ipsum',
        //         'Agama' => 'Lorem ipsum',
        //         'Jenis Kelamin' => 'Lorem ipsum',
        //         'Status Perkawinan' => 'Lorem ipsum',
        //         'Instansi Induk' => 'Lorem ipsum',
        //         'Satuan Organisasi' => 'Lorem ipsum',
        //         'Unit Kerja' => 'Lorem ipsum',
        //         'No. Karpeg/No. Karis/No. Karsu' => 'Lorem ipsum',
        //         'Masa Kerja Keseluruhan' => 'Lorem ipsum',
        //         'Masa Kerja Golongan' => 'Lorem ipsum',
        //         'NPWP' => 'Lorem ipsum',
        //         'Status Pegawai' => 'Lorem ipsum',
        //         'Komplek' => 'Lorem ipsum',
        //         'Nama Komplek' => 'Lorem ipsum',
        //         'Alamat Tempat Tinggal Saat Ini' => 'Jl. Anggrek Bulan 2 Blok F No. 13 Anggrek Loka Sektor 2.1. BSD Rawa Bunru, Serpong, Tangerang Selatan 15318',
        //         'No. Telepon Rumah' => 'Lorem ipsum',
        //         'No. HP' => 'Lorem ipsum',
        //         'Alamat Kantor' => 'Lorem ipsum',
        //         'No. Telepon Kantor' => 'Lorem ipsum',
        //         'Email' => 'Lorem ipsum',
        //         'Batas Usia Pensiun' => 'Lorem ipsum',
        //     ],
        //     'userCollege' => [
        //         [
        //             'grade' => 'SD/Sederajat',
        //             'school_name' => 'SDN Karang Tengah 2',
        //             'faculty' => '-',
        //             'major' => 'SD',
        //             'status' => 'Lulus',
        //             'year_graduate' => '1991',
        //             'desc' => '-',
        //         ],
        //         [
        //             'grade' => 'SMP/Sederajat',
        //             'school_name' => 'SMP Negeri 1 Ciledug',
        //             'faculty' => '-',
        //             'major' => 'SMP',
        //             'status' => 'Lulus',
        //             'year_graduate' => '1994',
        //             'desc' => '-',
        //         ],
        //         [
        //             'grade' => 'SMA/Sederajat',
        //             'school_name' => 'SMU Negeri 90 Jakarta',
        //             'faculty' => '-',
        //             'major' => 'SMA',
        //             'status' => 'Lulus',
        //             'year_graduate' => '2002',
        //             'desc' => '-',
        //         ],
        //         [
        //             'grade' => 'Akademik/D3/S.Muda',
        //             'school_name' => 'Universitas Indonesia',
        //             'faculty' => 'Ekonomi',
        //             'major' => 'D3 Administrasi Perkantoran dan Sekretaris',
        //             'status' => 'Lulus',
        //             'year_graduate' => '2004',
        //             'desc' => '-',
        //         ],
        //         [
        //             'grade' => 'Diploma IV / Strata I',
        //             'school_name' => 'Universitas Indonesia',
        //             'faculty' => 'Ekonomi',
        //             'major' => 'S1 Ekonomi Manajemen',
        //             'status' => 'Lulus',
        //             'year_graduate' => '2019',
        //             'desc' => '-',
        //         ],
        //     ],
        //     'userGrade' => [0, 0],
        //     'userGolongan' => [0, 0],
        //     'userTrainingStructural' => [0, 0, 0, 0, 0],
        //     'userTrainingFunctional' => [0, 0],
        //     'userTrainingTechnical' => [0, 0],
        //     'userAward' => [0, 0],
        //     'userSKP' => [0, 0],
        //     'userPerformance' => [0, 0],
        //     'userPunishment' => [0, 0],
        //     'userFamily' => [0, 0, 0],
        //     'userPaidLeave' => [0, 0],
        //     'userNotes' => [0, 0],
        // ]);

        $tmp = sys_get_temp_dir();

        $pdf = Pdf::loadview('exports/user', [
            'pegawai' => [1, 2, 3, 4, 5, 1, 2, 3, 4, 5, 1, 2, 3, 4, 5, 1, 2, 3, 4, 5, 1, 2, 3, 4, 5, 1, 2, 3, 4, 5, 1, 2, 3, 4, 5, 1, 2, 3, 4, 5, 1, 2, 3, 4, 5, 1, 2, 3, 4, 5, 1, 2, 3, 4, 5, 1, 2, 3, 4, 5, 1, 2, 3, 4, 5, 1, 2, 3, 4, 5, 1, 2, 3, 4, 5, 1, 2, 3, 4, 5],
            'userProfile' => [
                'Tempat, tanggal lahir' => 'Lorem ipsum',
                'Agama' => 'Lorem ipsum',
                'Jenis Kelamin' => 'Lorem ipsum',
                'Status Perkawinan' => 'Lorem ipsum',
                'Instansi Induk' => 'Lorem ipsum',
                'Satuan Organisasi' => 'Lorem ipsum',
                'Unit Kerja' => 'Lorem ipsum',
                'No. Karpeg/No. Karis/No. Karsu' => 'Lorem ipsum',
                'Masa Kerja Keseluruhan' => 'Lorem ipsum',
                'Masa Kerja Golongan' => 'Lorem ipsum',
                'NPWP' => 'Lorem ipsum',
                'Status Pegawai' => 'Lorem ipsum',
                'Komplek' => 'Lorem ipsum',
                'Nama Komplek' => 'Lorem ipsum',
                'Alamat Tempat Tinggal Saat Ini' => 'Jl. Anggrek Bulan 2 Blok F No. 13 Anggrek Loka Sektor 2.1. BSD Rawa Bunru, Serpong, Tangerang Selatan 15318',
                'No. Telepon Rumah' => 'Lorem ipsum',
                'No. HP' => 'Lorem ipsum',
                'Alamat Kantor' => 'Lorem ipsum',
                'No. Telepon Kantor' => 'Lorem ipsum',
                'Email' => 'Lorem ipsum',
                'Batas Usia Pensiun' => 'Lorem ipsum',
            ],
            'userCollege' => [
                [
                    'grade' => 'SD/Sederajat',
                    'school_name' => 'SDN Karang Tengah 2',
                    'faculty' => '-',
                    'major' => 'SD',
                    'status' => 'Lulus',
                    'year_graduate' => '1991',
                    'desc' => '-',
                ],
                [
                    'grade' => 'SMP/Sederajat',
                    'school_name' => 'SMP Negeri 1 Ciledug',
                    'faculty' => '-',
                    'major' => 'SMP',
                    'status' => 'Lulus',
                    'year_graduate' => '1994',
                    'desc' => '-',
                ],
                [
                    'grade' => 'SMA/Sederajat',
                    'school_name' => 'SMU Negeri 90 Jakarta',
                    'faculty' => '-',
                    'major' => 'SMA',
                    'status' => 'Lulus',
                    'year_graduate' => '2002',
                    'desc' => '-',
                ],
                [
                    'grade' => 'Akademik/D3/S.Muda',
                    'school_name' => 'Universitas Indonesia',
                    'faculty' => 'Ekonomi',
                    'major' => 'D3 Administrasi Perkantoran dan Sekretaris',
                    'status' => 'Lulus',
                    'year_graduate' => '2004',
                    'desc' => '-',
                ],
                [
                    'grade' => 'Diploma IV / Strata I',
                    'school_name' => 'Universitas Indonesia',
                    'faculty' => 'Ekonomi',
                    'major' => 'S1 Ekonomi Manajemen',
                    'status' => 'Lulus',
                    'year_graduate' => '2019',
                    'desc' => '-',
                ],
            ],
            'userGrade' => [0, 0],
            'userGolongan' => [0, 0],
            'userTrainingStructural' => [0, 0, 0, 0, 0],
            'userTrainingFunctional' => [0, 0],
            'userTrainingTechnical' => [0, 0],
            'userAward' => [0, 0],
            'userSKP' => [0, 0],
            'userPerformance' => [0, 0],
            'userPunishment' => [0, 0],
            'userFamily' => [0, 0, 0],
            'userPaidLeave' => [0, 0],
            'userNotes' => [0, 0],
        ]);
        $pdf->set_option('isHtml5ParserEnabled', true);
        $pdf->set_paper("A4", "portrait");
        $pdf->set_option('isRemoteEnabled', true);
        $pdf->set_option('fontDir', $tmp);
        $pdf->set_option('fontCache', $tmp);
        $pdf->set_option('tempDir', $tmp);
        return $pdf->download('user-pdf.pdf');
    }
}
