<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'email|unique:users,email',
            'name' => 'required|max:160',
            'photo_profile' => 'file|extensions:jpg,jpeg,png|max:2048|dimensions:min_width=100,min_height=200',
            'id_number' => 'numeric|digits:16|unique:users,id_number',
            'employee_id_number' => 'numeric|min:5|max:10|unique:users,employee_id_number',
            'employee_registration_number' => 'numeric|min:5|max:10|unique:users,employee_registration_number',
            'place_of_birth' => 'required|max:160',
            'date_of_birth' => 'required|date',
            'religion' => 'required|in:1,2,3,4,5,6',
            'gender' => 'required|boolean',
            'marital_status' => 'required|in:1,2,3,4,5',
            'employment_type_id' => 'required',
            'grade_id' => 'numeric',
            'grade_effective_date' => 'date',
            'position_id' => 'numeric',
            'echelon_id' => 'numeric',
            'echelon_effective_date' => 'date',
            'institution_id' => 'numeric',
            'organization_id' => 'numeric',
            'work_unit_id' => 'numeric',
            'employee_id_card_number' => 'numeric|min:5|max:10|unique:users,employee_id_card_number',
            'employee_id_card' => 'file|extensions:jpg,jpeg,png,pdf|max:2048',
            'wife_id_card_number' => 'numeric|min:5|max:10|unique:users,wife_id_card_number',
            'husband_id_card_number' => 'numeric|min:5|max:10|unique:users,husband_id_card_number',
            'id_tax' => 'numeric|digits:16|unique:users,id_tax',
            'employment_status' => 'boolean',
            'inner_housing_complex' => 'boolean',
            'current_address' => 'max:160',
            'home_phone_number' => 'numeric|max:14',
            'mobile_phone' => 'numeric|max:14',
            'office_address' => 'max:160',
            'office_phone_number' => 'numeric|max:14',
            'description' => 'max:160',
            'type' => 'required|in:1,2,3',
        ];
    }

    /**
     * Return error messages
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'email.email' => 'Format email harus sesuai.',
            'email.unique' => 'Email sudah terdaftar.',
            'name.required' => 'Nama tidak boleh kosong.',
            'name.max' => 'Nama tidak boleh lebih dari 160 karakter.',
            'photo_profile.file' => 'Foto profil harus berupa file.',
            'photo_profile.extensions' => 'Foto profil harus berupa jpg, jpeg atau png.',
            'photo_profile.max' => 'Ukuran foto profil tidak boleh lebih dari 2MB.',
            'id_number.numeric' => 'NIK harus berupa angka.',
            'id_number.digits' => 'NIK harus terdiri dari 16 digit angka.',
            'id_number.unique' => 'NIK sudah terdaftar.',
            'employee_id_number.numeric' => 'NIP harus berupa angka.',
            'employee_id_number.min' => 'NIP tidak boleh kurang dari 5 digit angka.',
            'employee_id_number.max' => 'NIP tidak boleh lebih dari 10 digit angka.',
            'employee_id_number.unique' => 'NIP sudah terdaftar.',
            'employee_registration_number.numeric' => 'NRP harus berupa angka.',
            'employee_registration_number.min' => 'NRP tidak boleh kurang dari 5 digit angka.',
            'employee_registration_number.max' => 'NRP tidak boleh lebih dari 10 digit angka.',
            'employee_registration_number.unique' => 'NRP sudah terdaftar.',
            'place_of_birth.required' => 'Tempat lahir tidak boleh kosong.',
            'place_of_birth.max' => 'Tempat lahir tidak boleh lebih dari 160 karakter.',
            'date_of_birth.required' => 'Tanggal lahir tidak boleh kosong.',
            'date_of_birth.date' => 'Tanggal lahir harus berupa tanggal.',
            'religion.required' => 'Agama tidak boleh kosong.',
            'religion.in' => 'Agama harus diantara 1, 2, 3, 4, 5 atau 6.',
            'gender.required' => 'Jenis kelamin tidak boleh kosong.',
            'gender.boolean' => 'Jenis kelamin harus berupa boolean.',
            'marital_status.required' => 'Status perkawinan tidak boleh kosong.',
            'marital_status.in' => 'Status perkawinan harus diantara 1, 2, 3, 4 atau 5.',
            'grade_id.numeric' => 'Golongan harus berupa angka.',
            'grade_effective_date.date' => 'Tanggal efektif golongan harus berupa tanggal.',
            'position_id.numeric' => 'Jabatan harus berupa angka.',
            'echelon_id.numeric' => 'Eselon harus berupa angka.',
            'echelon_effective_date.date' => 'Tanggal efektif eselon harus berupa tanggal.',
            'institution_id.numeric' => 'Institusi harus berupa angka.',
            'organization_id.numeric' => 'Organisasi harus berupa angka.',
            'work_unit_id.numeric' => 'Unit kerja harus berupa angka.',
            'employee_id_card_number.numeric' => 'Nomor kartu pegawai harus berupa angka.',
            'employee_id_card_number.min' => 'Nomor kartu pegawai tidak boleh kurang dari 5 digit angka.',
            'employee_id_card_number.max' => 'Nomor kartu pegawai tidak boleh lebih dari 10 digit angka.',
            'employee_id_card_number.unique' => 'Nomor kartu pegawai sudah terdaftar.',
            'employee_id_card.file' => 'Kartu pegawai harus berupa file.',
            'employee_id_card.extensions' => 'Kartu pegawai harus berupa jpg,jpeg,png atau pdf.',
            'employee_id_card.max' => 'Ukuran kartu pegawai tidak boleh lebih dari 2048kb.',
            'wife_id_card_number.numeric' => 'Nomor kartu istri harus berupa angka.',
            'wife_id_card_number.min' => 'Nomor kartu istri tidak boleh kurang dari 5 digit angka.',
            'wife_id_card_number.max' => 'Nomor kartu istri tidak boleh lebih dari 10 digit angka.',
            'wife_id_card_number.unique' => 'Nomor kartu istri sudah terdaftar.',
            'husband_id_card_number.numeric' => 'Nomor kartu suami harus berupa angka.',
            'husband_id_card_number.min' => 'Nomor kartu suami tidak boleh kurang dari 5 digit angka.',
            'husband_id_card_number.max' => 'Nomor kartu suami tidak boleh lebih dari 10 digit angka.',
            'husband_id_card_number.unique' => 'Nomor kartu suami sudah terdaftar.',
            'id_tax.numeric' => 'NPWP harus berupa angka.',
            'id_tax.digits' => 'NPWP harus terdiri dari 16 digit angka.',
            'id_tax.unique' => 'NPWP sudah terdaftar.',
            'employment_status.boolean' => 'Status pegawai harus boolean.',
            'inner_housing_complex.boolean' => 'Komplek harus berupa boolean.',
            'current_address.max' => 'Alamat saat ini tidak boleh lebih dari 160 karakter.',
            'home_phone_number.numeric' => 'Nomor telepon rumah harus berupa angka.',
            'home_phone_number.max' => 'Nomor telepon rumah tidak boleh lebih dari 14 digit angka.',
            'mobile_phone.numeric' => 'Nomor HP harus berupa angka.',
            'mobile_phone.max' => 'Nomor HP tidak boleh lebih dari 14 digit angka.',
            'office_address.max' => 'Alamat kantor tidak boleh lebih dari 160 karakter.',
            'office_phone_number.numeric' => 'Nomor telepon kantor harus berupa angka.',
            'office_phone_number.max' => 'Nomor telepon kantor tidak boleh lebih dari 14 digit angka.',
            'description.max' => 'Keterangan tidak boleh lebih dari 160 karakter.',
            'type.required' => 'Tipe pegawai tidak boleh kosong.',
            'type.in' => 'Tipe pegawai diantara 1, 2 atau 3',
        ];
    }

    /**
     * Description for scribe
     *
     * @return array
     */
    public function bodyParameters(): array
    {
        return [
            'email' => [
                'description' => 'Refers to the Email of Employee.',
                'example' => 'padmi@wapresri.go.id',
            ],
            'name' => [
                'description' => 'Refers to the Name of Employee.',
                'example' => 'Padmi Riyanti S.Sos',
            ],
            'photo_profile' => [
                'description' => 'Refers to the Photo Profile of Employee.',
                'example' => public_path('/img/logo.svg'),
            ],
            'id_number' => [
                'description' => 'Refers to the ID Number of Employee.',
                'example' => '3279034401000001',
            ],
            'employee_id_number' => [
                'description' => 'Refers to the Employee ID Number of Employee.',
                'example' => '00010015',
            ],
            'employee_registration_number' => [
                'description' => 'Refers to the Employee Registration Number of Employee.',
                'example' => '00010015',
            ],
            'place_of_birth' => [
                'description' => 'Refers to the Place of Birth of Employee.',
                'example' => 'Jakarta',
            ],
            'date_of_birth' => [
                'description' => 'Refers to the Date of Birth of Employee.',
                'example' => '1988-12-22',
            ],
            'religion' => [
                'description' => 'Refers to the Religion of Employee. 1=Islam, 2=Kristen, 3=Katolik, 4=Hindu, 5=Buddha, 6=Konghucu',
                'example' => 1,
            ],
            'gender' => [
                'description' => 'Refers to the Gender of Employee. true=laki-laki, false=perempuan',
                'example' => true,
            ],
            'marital_status' => [
                'description' => 'Refers to the Marital Status of Employee. 1=Belum Menikah, 2=Menikah, 3=Cerai, 4=Janda, 5=Duda',
                'example' => 1,
            ],
            'employment_type_id' => [
                'description' => 'Refers to the Employment Type ID of Employee.',
                'example' => 1,
            ],
            'grade_id' => [
                'description' => 'Refers to the Grade ID of Employee.',
                'example' => 1,
            ],
            'grade_effective_date' => [
                'description' => 'Refers to the Grade Effective Date of Employee.',
                'example' => '2010-10-21',
            ],
            'position_id' => [
                'description' => 'Refers to the Position ID of Employee.',
                'example' => 1,
            ],
            'echelon_id' => [
                'description' => 'Refers to the Echelon ID of Employee.',
                'example' => 1,
            ],
            'echelon_effective_date' => [
                'description' => 'Refers to the Echelon Effective Date of Employee.',
                'example' => '2013-07-23',
            ],
            'institution_id' => [
                'description' => 'Refers to the Institution ID of Employee.',
                'example' => 1,
            ],
            'organization_id' => [
                'description' => 'Refers to the Organization ID of Employee.',
                'example' => 1,
            ],
            'work_unit_id' => [
                'description' => 'Refers to the Work Unit ID of Employee.',
                'example' => 1,
            ],
            'employee_id_card_number' => [
                'description' => 'Refers to the Employee ID Card Number of Employee.',
                'example' => '00010015',
            ],
            'employee_id_card' => [
                'description' => 'Refers to the Employee ID Card of Employee.',
                'example' => public_path('/img/logo.svg'),
            ],
            'wife_id_card_number' => [
                'description' => 'Refers to the Wife ID Card Number of Employee.',
                'example' => '00010015',
            ],
            'husband_id_card_number' => [
                'description' => 'Refers to the Husband ID Card Number of Employee.',
                'example' => '00010015',
            ],
            'id_tax' => [
                'description' => 'Refers to the ID Tax of Employee.',
                'example' => '12345678901234',
            ],
            'employment_status' => [
                'description' => 'Refers to the Employment Status of Employee. true=aktif, false=tidak aktif',
                'example' => true,
            ],
            'inner_housing_complex' => [
                'description' => 'Refers to the Inner Housing Complex of Employee. true=dalam, false=luar',
                'example' => true,
            ],
            'current_address' => [
                'description' => 'Refers to the Current Address of Employee.',
                'example' => 'Jalan Mawar No. 123, Kelurahan Bunga Indah, Kecamatan Kota Baru, Jakarta, Kode Pos 12345',
            ],
            'home_phone_number' => [
                'description' => 'Refers to the Home Phone Number of Employee.',
                'example' => '02112345678',
            ],
            'mobile_phone' => [
                'description' => 'Refers to the Mobile Phone of Employee.',
                'example' => '6281234567890',
            ],
            'office_address' => [
                'description' => 'Refers to the Office Address of Employee.',
                'example' => 'Jalan Mawar No. 123, Kelurahan Bunga Indah, Kecamatan Kota Baru, Jakarta, Kode Pos 12345',
            ],
            'office_phone_number' => [
                'description' => 'Refers to the Office Phone Number of Employee.',
                'example' => '02112345678',
            ],
            'description' => [
                'description' => 'Refers to the Description of Employee.',
                'example' => 'Keterangan pegawai',
            ],
            'type' => [
                'description' => 'Refers to the Type of Employee. 1=ASN, 2=NON-ASN, 3=OUTSOURCE',
                'example' => 1,
            ],
        ];
    }
}
