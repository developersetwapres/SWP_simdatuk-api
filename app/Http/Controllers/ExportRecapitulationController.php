<?php

namespace App\Http\Controllers;

use App\Repositories\RecapitulationRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * @group Export
 */
class ExportRecapitulationController extends Controller
{
    public function __construct(
        Request $request,
        RecapitulationRepository $recapitulationRepository
    ) {
        $this->request = $request;
        $this->posted = $request->except('_token', '_method');
        $this->recapitulationRepository = $recapitulationRepository;
    }

    /**
     * Export Recapitulation
     *
     * Export all recapitulation data, asn, non asn and outsource to .pdf
     * @group Export
     * @authenticated
     * @urlParam type Refers to the Type of recapitulation 1=All Recapitulation, 2=ASN, 3=Non ASN, 4=Outsource. Example: 1
     * @response 404 {"code": 404,"message": "Tipe tidak ditemukan. harap gunakan 1, 2, 3 atau 4.","data": null}
     */
    public function recapitulation()
    {
        if ($this->request->type == 1) {
            return $this->allRecapitulation();
        } else if ($this->request->type == 2) {
            return $this->asn();
        } else if ($this->request->type == 3) {
            return $this->nonAsn();
        } else if ($this->request->type == 4) {
            return $this->outsource();
        } else {
            return $this->response(404, 'Tipe tidak ditemukan. harap gunakan 1, 2, 3 atau 4.');
        }
    }

    private function allRecapitulation()
    {
        $pejabat = $this->recapitulationRepository->getPejabatPimpinanAndFungsional();
        $pelaksana = $this->recapitulationRepository->getPejabatPelaksana();
        $nonActive = $this->recapitulationRepository->getNonActiveAsn();
        $title = 'Rekapitulasi Pegawai';
        $date = Carbon::now()->timezone('Asia/Jakarta')->locale('id')->isoFormat('D MMMM Y');
        $tmp = sys_get_temp_dir();
        $pdf = Pdf::loadview('exports/recapitulation', [
            'title' => $title . ' Sekretariat Wakil Presiden RI',
            'date' => $date,
            'data' => [
                [
                    'title' => 'Pejabat Pimpinan',
                    'body' => 'Total : ' . $pejabat->total_pejabat_pimpinan,
                    'type' => 1,
                ],
                [
                    'title' => 'Pejabat Pimpinan Tinggi Madya (Eselon I)',
                    'body' => $pejabat->echelon1,
                    'type' => 2,
                ],
                [
                    'title' => 'Pejabat Pimpinan Tinggi Pratama (Eselon II)',
                    'body' => $pejabat->echelon2,
                    'type' => 2,
                ],
                [
                    'title' => 'Pejabat Administrasi (Eselon III)',
                    'body' => $pejabat->echelon3,
                    'type' => 2,
                ],
                [
                    'title' => 'Pejabat Pengawas (Eselon IV)',
                    'body' => $pejabat->echelon4,
                    'type' => 2,
                ],
                [
                    'title' => 'Pejabat Pelaksana',
                    'body' => 'Total : ' . $pelaksana->total,
                    'type' => 1,
                ],
                [
                    'title' => 'Pejabat Pelaksana Golongan IV',
                    'body' => $pelaksana->golongan4,
                    'type' => 2,
                ],
                [
                    'title' => 'Pejabat Pelaksana Golongan III',
                    'body' => $pelaksana->golongan3,
                    'type' => 2,
                ],
                [
                    'title' => 'Pejabat Pelaksana Golongan II',
                    'body' => $pelaksana->golongan2,
                    'type' => 2,
                ],
                [
                    'title' => 'Pejabat Pelaksana Perbantuan TNI dan POLRI',
                    'body' => $pelaksana->tnipolri,
                    'type' => 2,
                ],
                [
                    'title' => 'Pejabat Fungsional Keahlian',
                    'body' => 'Total : ' . $pejabat->total_pejabat_fungsional_keahlian,
                    'type' => 1,
                ],
                [
                    'title' => 'Pejabat Fungsional Ahli Utama',
                    'body' => $pejabat->ahli_utama,
                    'type' => 2,
                ],
                [
                    'title' => 'Pejabat Fungsional Ahli Madya',
                    'body' => $pejabat->ahli_madya,
                    'type' => 2,
                ],
                [
                    'title' => 'Pejabat Fungsional Ahli Muda',
                    'body' => $pejabat->ahli_muda,
                    'type' => 2,
                ],
                [
                    'title' => 'Pejabat Fungsional Ahli Pertama',
                    'body' => $pejabat->ahli_pertama,
                    'type' => 2,
                ],
                [
                    'title' => 'Pejabat Fungsional Keterampilan',
                    'body' => 'Total : ' . $pejabat->total_pejabat_fungsional_keterampilan,
                    'type' => 1,
                ],
                [
                    'title' => 'Pejabat Fungsional Penyelia',
                    'body' => $pejabat->penyelia,
                    'type' => 2,
                ],
                [
                    'title' => 'Pejabat Fungsional Mahir',
                    'body' => $pejabat->mahir,
                    'type' => 2,
                ],
                [
                    'title' => 'Pejabat Fungsional Terampil',
                    'body' => $pejabat->terampil,
                    'type' => 2,
                ],
                [
                    'title' => 'Pejabat Fungsional Pemula',
                    'body' => $pejabat->pemula,
                    'type' => 2,
                ],
                [
                    'title' => 'Pejabat Kemensetneg Yang Diperbantukan di Setwapres',
                    'body' => 'Total : 15',
                    'type' => 1,
                ],
                [
                    'title' => 'Pejabat Struktural',
                    'body' => '1',
                    'type' => 2,
                ],
                [
                    'title' => 'Pejabat Pelaksana',
                    'body' => '1',
                    'type' => 2,
                ],
                [
                    'title' => 'Pejabat Fungsional',
                    'body' => '13',
                    'type' => 2,
                ],
                [
                    'title' => 'Aparatur Sipil Negara (ASN) Aktif + Perbantuan TNI/POLRI Pelaksana',
                    'body' => 'Total : 15',
                    'type' => 3,
                ],
                [
                    'title' => 'Tugas Belajar Luar Negeri (TBLN)',
                    'body' => $nonActive->tbln,
                    'type' => 1,
                ],
                [
                    'title' => 'Cuti Diluar Tanggungan Negara (CLTN)',
                    'body' => $nonActive->cltn,
                    'type' => 1,
                ],
                [
                    'title' => 'Tidak Aktif (Non Jabatan)',
                    'body' => $nonActive->nonactive,
                    'type' => 1,
                ],
                [
                    'title' => 'Aparatur Sipil Negara (ASN) Non Aktif',
                    'body' => 'Total : ' . $nonActive->total,
                    'type' => 3,
                ],
                [
                    'title' => 'Non Aparatur Sipil Negara (Non ASN)',
                    'body' => 'Total : 74',
                    'type' => 1,
                ],
                [
                    'title' => 'Staf Khusus Wakil Presiden',
                    'body' => '10',
                    'type' => 2,
                ],
                [
                    'title' => 'Asisten Staf Khusus Wakil Presiden',
                    'body' => '20',
                    'type' => 2,
                ],
                [
                    'title' => 'Pembantu Asisten Staf Khusus Wakil Presiden',
                    'body' => '5',
                    'type' => 2,
                ],
                [
                    'title' => 'Anggota Tim Ahli Wakil Presiden',
                    'body' => '12',
                    'type' => 2,
                ],
                [
                    'title' => 'Staf Pada Sekretaris Pribadi Istri Wakil Presiden',
                    'body' => '1',
                    'type' => 2,
                ],
                [
                    'title' => 'Staf Kerumahtanggaan Pada Kediaman Wakil Presiden',
                    'body' => '1',
                    'type' => 2,
                ],
                [
                    'title' => 'Sekretariat Pada Staf Khusus Wakil Presiden (PTT dari SETKAB)',
                    'body' => '3',
                    'type' => 2,
                ],
                [
                    'title' => 'Ajudan Wakil Presiden dan Istri Wakil Presiden (Perbantuan TNI dan POLRI)',
                    'body' => '8',
                    'type' => 2,
                ],
                [
                    'title' => 'Dokter Pribadi Wakil Presiden',
                    'body' => '4',
                    'type' => 2,
                ],
                [
                    'title' => 'Pengemudi VVIP (Perbantuan TNI dan POLRI)',
                    'body' => '10',
                    'type' => 2,
                ],
                [
                    'title' => 'Tim',
                    'body' => 'Total : 24',
                    'type' => 1,
                ],
                [
                    'title' => 'Tim Nasional Percepatan Stunting (TPPS)',
                    'body' => '24',
                    'type' => 2,
                ],
                [
                    'title' => 'Non Aparatur Sipil Negara (Non ASN) + Tim',
                    'body' => 'Total : 162',
                    'type' => 3,
                ],
                [
                    'title' => 'Tenaga Outsourcing',
                    'body' => 'Total : 191',
                    'type' => 1,
                ],
                [
                    'title' => 'Pengemudi',
                    'body' => '38',
                    'type' => 2,
                ],
                [
                    'title' => 'Petugas Kebersihan Gedung',
                    'body' => '51',
                    'type' => 2,
                ],
                [
                    'title' => 'Petugas Perawatan Kolam',
                    'body' => '2',
                    'type' => 2,
                ],
                [
                    'title' => 'Petugas Taman',
                    'body' => '24',
                    'type' => 2,
                ],
                [
                    'title' => 'Pramusaji/Pramubakti',
                    'body' => '39',
                    'type' => 2,
                ],
                [
                    'title' => 'Teknisi Jaringan',
                    'body' => '2',
                    'type' => 2,
                ],
                [
                    'title' => 'Teknisi Komputer',
                    'body' => '11',
                    'type' => 2,
                ],
                [
                    'title' => 'Teknisi Mekanikal dan Elektrikal',
                    'body' => '24',
                    'type' => 2,
                ],
                [
                    'title' => 'Tenaga Outsourcing',
                    'body' => 'Total : 191',
                    'type' => 3,
                ],
            ],
        ]);
        $pdf->set_option('isHtml5ParserEnabled', true);
        $pdf->set_paper("A4", "portrait");
        $pdf->set_option('isRemoteEnabled', true);
        $pdf->set_option('fontDir', $tmp);
        $pdf->set_option('fontCache', $tmp);
        $pdf->set_option('tempDir', $tmp);
        return $pdf->download($title . ' - ' . $date . '.pdf');
    }

    private function asn()
    {
        $title = 'Rekapitulasi Pegawai ASN';
        $date = Carbon::now()->timezone('Asia/Jakarta')->locale('id')->isoFormat('D MMMM Y');
        $tmp = sys_get_temp_dir();
        $pdf = Pdf::loadview('exports/recapitulation', [
            'title' => $title . ' Sekretariat Wakil Presiden RI',
            'date' => $date,
            'data' => [
                [
                    'title' => 'Kepala Sekretariat Wakil Presiden',
                    'body' => '1',
                    'type' => 2,
                ],
                [
                    'title' => 'Deputi Bidang Dukungan Kebijakan Pembangunan Ekonomi dan Peningkatan Daya Saing',
                    'body' => '24',
                    'type' => 2,
                ],
                [
                    'title' => 'Deputi Bidang Dukungan Kebijakan Pembangunan Manusia dan Pemerataan Pembangunan',
                    'body' => '26',
                    'type' => 2,
                ],
                [
                    'title' => 'Deputi Bidang Dukungan Kebijakan Pemerintah dan Wawasan Kebangsaan',
                    'body' => '31',
                    'type' => 2,
                ],
                [
                    'title' => 'Deputi Bidang Administrasi',
                    'body' => '186',
                    'type' => 2,
                ],
                [
                    'title' => 'Kementerian Sekretariat Negara',
                    'body' => '186',
                    'type' => 2,
                ],
                [
                    'title' => 'Unit Kerja',
                    'body' => '283',
                    'type' => 3,
                ],
                [
                    'title' => 'Jabatan Pimpinan Tinggi',
                    'body' => 'Total : 19',
                    'type' => 1,
                ],
                [
                    'title' => 'Jabatan Pimpinan Madya',
                    'body' => '4',
                    'type' => 2,
                ],
                [
                    'title' => 'Jabatan Pimpinan Pratama',
                    'body' => '15',
                    'type' => 2,
                ],
                [
                    'title' => 'Jabatan Administrasi',
                    'body' => 'Total : 128',
                    'type' => 1,
                ],
                [
                    'title' => 'Administrator',
                    'body' => '10',
                    'type' => 2,
                ],
                [
                    'title' => 'Pengawas',
                    'body' => '23',
                    'type' => 2,
                ],
                [
                    'title' => 'Pelaksana',
                    'body' => '95',
                    'type' => 2,
                ],
                [
                    'title' => 'Jabatan Fungsional',
                    'body' => 'Total : 128',
                    'type' => 1,
                ],
                [
                    'title' => 'Keahlian',
                    'body' => '120',
                    'type' => 1,
                ],
                [
                    'title' => 'Ahli Utama',
                    'body' => '0',
                    'type' => 2,
                ],
                [
                    'title' => 'Ahli Madya',
                    'body' => '33',
                    'type' => 2,
                ],
                [
                    'title' => 'Ahli Muda',
                    'body' => '68',
                    'type' => 2,
                ],
                [
                    'title' => 'Ahli Pertama',
                    'body' => '12',
                    'type' => 2,
                ],
                [
                    'title' => 'Keterampilan',
                    'body' => '8',
                    'type' => 1,
                ],
                [
                    'title' => 'Penyelia',
                    'body' => '4',
                    'type' => 2,
                ],
                [
                    'title' => 'Mahir',
                    'body' => '0',
                    'type' => 2,
                ],
                [
                    'title' => 'Terampil',
                    'body' => '4',
                    'type' => 2,
                ],
                [
                    'title' => 'Pemula',
                    'body' => '0',
                    'type' => 2,
                ],
                [
                    'title' => 'Aparatur Sipil Negara (ASN) + Perbantuan TNI/POLRI Pelaksana',
                    'body' => 'Total : 15',
                    'type' => 3,
                ],
                [
                    'title' => 'Pembina Utama (IV/e)',
                    'body' => '3',
                    'type' => 2,
                ],
                [
                    'title' => 'Pembina Utama Madya (IV/d)',
                    'body' => '8',
                    'type' => 2,
                ],
                [
                    'title' => 'Pembina Utama Muda (IV/c)',
                    'body' => '7',
                    'type' => 2,
                ],
                [
                    'title' => 'Pembina Tingkat I (IV/b)',
                    'body' => '41',
                    'type' => 2,
                ],
                [
                    'title' => 'Pembina (IV/a)',
                    'body' => '34',
                    'type' => 2,
                ],
                [
                    'title' => 'Penata Tingkat I (III/d)',
                    'body' => '59',
                    'type' => 2,
                ],
                [
                    'title' => 'Penata (III/c)',
                    'body' => '24',
                    'type' => 2,
                ],
                [
                    'title' => 'Penata Muda Tingkat I (II/b)',
                    'body' => '24',
                    'type' => 2,
                ],
                [
                    'title' => 'Penata Muda (III/a)',
                    'body' => '30',
                    'type' => 2,
                ],
                [
                    'title' => 'Pengatur Tingkat I (II/d)',
                    'body' => '27',
                    'type' => 2,
                ],
                [
                    'title' => 'Pengatur (II/c)',
                    'body' => '15',
                    'type' => 2,
                ],
                [
                    'title' => 'Pengatur Muda Tingkat I (II/b)',
                    'body' => '9',
                    'type' => 2,
                ],
                [
                    'title' => 'Pengatur Muda (II/a)',
                    'body' => '2',
                    'type' => 2,
                ],
                [
                    'title' => 'Golongan',
                    'body' => 'Total : 283',
                    'type' => 3,
                ],
                [
                    'title' => 'Tugas Belajar Luar Negeri (TBLN)',
                    'body' => '3',
                    'type' => 2,
                ],
                [
                    'title' => 'Cuti Diluar Tanggungan Negara (CLTN)',
                    'body' => '3',
                    'type' => 2,
                ],
                [
                    'title' => 'Pegawai Non Aktif',
                    'body' => '6',
                    'type' => 3,
                ],
                [
                    'title' => 'Strata III',
                    'body' => '0',
                    'type' => 2,
                ],
                [
                    'title' => 'Strata II',
                    'body' => '0',
                    'type' => 2,
                ],
                [
                    'title' => 'Diploma IV/Strata I',
                    'body' => '68',
                    'type' => 2,
                ],
                [
                    'title' => 'Akademi/Diploma III/Sarjana Muda',
                    'body' => '0',
                    'type' => 2,
                ],
                [
                    'title' => 'Diploma I/II',
                    'body' => '0',
                    'type' => 2,
                ],
                [
                    'title' => 'SLTA/Sederajat',
                    'body' => '0',
                    'type' => 2,
                ],
                [
                    'title' => 'SLTP/Sederajat',
                    'body' => '0',
                    'type' => 2,
                ],
                [
                    'title' => 'Pendidikan',
                    'body' => 'Total : 0',
                    'type' => 3,
                ],
                [
                    'title' => 'Laki-laki',
                    'body' => '0',
                    'type' => 2,
                ],
                [
                    'title' => 'Perempuan',
                    'body' => '0',
                    'type' => 2,
                ],
                [
                    'title' => 'Jenis Kelamin',
                    'body' => 'Total : 0',
                    'type' => 3,
                ],
            ],
        ]);
        $pdf->set_option('isHtml5ParserEnabled', true);
        $pdf->set_paper("A4", "portrait");
        $pdf->set_option('isRemoteEnabled', true);
        $pdf->set_option('fontDir', $tmp);
        $pdf->set_option('fontCache', $tmp);
        $pdf->set_option('tempDir', $tmp);
        return $pdf->download($title . ' - ' . $date . '.pdf');
    }

    private function nonAsn()
    {
        $title = 'Rekapitulasi Pegawai Non ASN';
        $date = Carbon::now()->timezone('Asia/Jakarta')->locale('id')->isoFormat('D MMMM Y');
        $tmp = sys_get_temp_dir();
        $pdf = Pdf::loadview('exports/recapitulation', [
            'title' => $title . ' Sekretariat Wakil Presiden RI',
            'date' => $date,
            'data' => [
                [
                    'title' => 'Staf Khusus Wakil Presiden',
                    'body' => '10',
                    'type' => 2,
                ],
                [
                    'title' => 'Asisten Staf Khusus Wakil Presiden',
                    'body' => '20',
                    'type' => 2,
                ],
                [
                    'title' => 'Pembantu Asisten Staf Khusus Wakil Presiden',
                    'body' => '5',
                    'type' => 2,
                ],
                [
                    'title' => 'Anggota Tim Ahli Wakil Presiden',
                    'body' => '12',
                    'type' => 2,
                ],
                [
                    'title' => 'Staf Pada Sekretaris Pribadi Istri Wakil Presiden',
                    'body' => '1',
                    'type' => 2,
                ],
                [
                    'title' => 'Staf Kerumahtanggaan Pada Kediaman Wakil Presiden',
                    'body' => '1',
                    'type' => 2,
                ],
                [
                    'title' => 'Sekretariat Pada Staf Khusus Wakil Presiden (PTT dari SETKAB)',
                    'body' => '3',
                    'type' => 2,
                ],
                [
                    'title' => 'Ajudan Wakil Presiden dan Istri Wakil Presiden (Perbantuan TNI dan POLRI)',
                    'body' => '8',
                    'type' => 2,
                ],
                [
                    'title' => 'Dokter Pribadi Wakil Presiden',
                    'body' => '4',
                    'type' => 2,
                ],
                [
                    'title' => 'Pengemudi VVIP (Perbantuan TNI dan POLRI)',
                    'body' => '10',
                    'type' => 2,
                ],
                [
                    'title' => 'Jabatan',
                    'body' => 'Total : 74',
                    'type' => 3,
                ],
                [
                    'title' => 'Tim Nasional Percepatan Penurunan Stunting (TPPS)',
                    'body' => '24',
                    'type' => 2,
                ],
                [
                    'title' => 'Tim',
                    'body' => 'Total : 24',
                    'type' => 3,
                ],
                [
                    'title' => 'Strata III',
                    'body' => '0',
                    'type' => 2,
                ],
                [
                    'title' => 'Strata II',
                    'body' => '0',
                    'type' => 2,
                ],
                [
                    'title' => 'Diploma IV/Strata I',
                    'body' => '68',
                    'type' => 2,
                ],
                [
                    'title' => 'Akademi/Diploma III/Sarjana Muda',
                    'body' => '0',
                    'type' => 2,
                ],
                [
                    'title' => 'Diploma I/II',
                    'body' => '0',
                    'type' => 2,
                ],
                [
                    'title' => 'SLTA/Sederajat',
                    'body' => '0',
                    'type' => 2,
                ],
                [
                    'title' => 'SLTP/Sederajat',
                    'body' => '0',
                    'type' => 2,
                ],
                [
                    'title' => 'Pendidikan',
                    'body' => 'Total : 0',
                    'type' => 3,
                ],
                [
                    'title' => 'Laki-laki',
                    'body' => '0',
                    'type' => 2,
                ],
                [
                    'title' => 'Perempuan',
                    'body' => '0',
                    'type' => 2,
                ],
                [
                    'title' => 'Jenis Kelamin',
                    'body' => 'Total : 0',
                    'type' => 3,
                ],
            ],
        ]);
        $pdf->set_option('isHtml5ParserEnabled', true);
        $pdf->set_paper("A4", "portrait");
        $pdf->set_option('isRemoteEnabled', true);
        $pdf->set_option('fontDir', $tmp);
        $pdf->set_option('fontCache', $tmp);
        $pdf->set_option('tempDir', $tmp);
        return $pdf->download($title . ' - ' . $date . '.pdf');
    }

    private function outsource()
    {
        $title = 'Rekapitulasi Pegawai Outsourcing';
        $date = Carbon::now()->timezone('Asia/Jakarta')->locale('id')->isoFormat('D MMMM Y');
        $tmp = sys_get_temp_dir();
        $pdf = Pdf::loadview('exports/recapitulation', [
            'title' => $title . ' Sekretariat Wakil Presiden RI',
            'date' => $date,
            'data' => [
                [
                    'title' => 'Pengemudi',
                    'body' => '38',
                    'type' => 2,
                ],
                [
                    'title' => 'Petugas Kebersihan Gedung',
                    'body' => '51',
                    'type' => 2,
                ],
                [
                    'title' => 'Petugas Perawatan Kolam',
                    'body' => '2',
                    'type' => 2,
                ],
                [
                    'title' => 'Petugas Taman',
                    'body' => '24',
                    'type' => 2,
                ],
                [
                    'title' => 'Pramusaji/Pramubakti',
                    'body' => '39',
                    'type' => 2,
                ],
                [
                    'title' => 'Teknisi Jaringan',
                    'body' => '2',
                    'type' => 2,
                ],
                [
                    'title' => 'Teknisi Komputer',
                    'body' => '11',
                    'type' => 2,
                ],
                [
                    'title' => 'Teknisi Mekanikal dan Elektrikal',
                    'body' => '24',
                    'type' => 2,
                ],
                [
                    'title' => 'Tenaga Outsourcing',
                    'body' => 'Total : 191',
                    'type' => 3,
                ],
                [
                    'title' => 'Strata III',
                    'body' => '0',
                    'type' => 2,
                ],
                [
                    'title' => 'Strata II',
                    'body' => '0',
                    'type' => 2,
                ],
                [
                    'title' => 'Diploma IV/Strata I',
                    'body' => '68',
                    'type' => 2,
                ],
                [
                    'title' => 'Akademi/Diploma III/Sarjana Muda',
                    'body' => '0',
                    'type' => 2,
                ],
                [
                    'title' => 'Diploma I/II',
                    'body' => '0',
                    'type' => 2,
                ],
                [
                    'title' => 'SLTA/Sederajat',
                    'body' => '0',
                    'type' => 2,
                ],
                [
                    'title' => 'SLTP/Sederajat',
                    'body' => '0',
                    'type' => 2,
                ],
                [
                    'title' => 'Pendidikan',
                    'body' => 'Total : 0',
                    'type' => 3,
                ],
                [
                    'title' => 'Laki-laki',
                    'body' => '0',
                    'type' => 2,
                ],
                [
                    'title' => 'Perempuan',
                    'body' => '0',
                    'type' => 2,
                ],
                [
                    'title' => 'Jenis Kelamin',
                    'body' => 'Total : 0',
                    'type' => 3,
                ],
            ],
        ]);
        $pdf->set_option('isHtml5ParserEnabled', true);
        $pdf->set_paper("A4", "portrait");
        $pdf->set_option('isRemoteEnabled', true);
        $pdf->set_option('fontDir', $tmp);
        $pdf->set_option('fontCache', $tmp);
        $pdf->set_option('tempDir', $tmp);
        return $pdf->download($title . ' - ' . $date . '.pdf');
    }
}
