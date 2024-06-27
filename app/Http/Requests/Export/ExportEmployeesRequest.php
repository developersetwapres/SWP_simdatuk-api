<?php

namespace App\Http\Requests\Export;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExportEmployeesRequest extends FormRequest
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
            'extension' => ['required', Rule::in(['xlsx', 'csv', 'pdf'])],
            'deputy' => 'array|min:1',
            'employee_type' => 'array|min:1|nullable',
            'echelons' => 'array|min:1|numeric|nullable',
            'grades' => 'array|min:1|numeric|nullable',
            'position_status' => 'array|min:1|nullable',
            'education' => 'array|min:1|nullable',
            'gender' => 'array|min:1|max:2|nullable',
            'age_range' => 'array|min:1|nullable',
            'marital_status' => 'array|min:1|nullable',
            'target_period' => 'nullable|in:Q1,Q2,Q3,Q4,Tahunan',
            'target_year' => 'nullable|date_format:Y',
            'work_behavior_rating' => 'nullable|numeric|in:1,2,3',
            'employee_performance_predicate' => 'nullable|numeric|in:1,2,3,4,5',
            'organizational_performance_achievement' => 'nullable|numeric|in:1,2,3',
            'credit_period' => 'nullable|numeric|in:1,2,3,4,5',
            'credit_year' => 'nullable|date_format:Y',
            'isName' => 'nullable|numeric|min:0|max:1',
            'isNip' => 'nullable|numeric|min:0|max:1',
            'isBirthPlaceDate' => 'nullable|numeric|min:0|max:1',
            'isAge' => 'nullable|numeric|min:0|max:1',
            'isReligion' => 'nullable|numeric|min:0|max:1',
            'isGender' => 'nullable|numeric|min:0|max:1',
            'isMaritalStatus' => 'nullable|numeric|min:0|max:1',
            'isEmployeeType' => 'nullable|numeric|min:0|max:1',
            'isAssistanceType' => 'nullable|numeric|min:0|max:1',
            'isOutsourcingType' => 'nullable|numeric|min:0|max:1',
            'isDateCPNS' => 'nullable|numeric|min:0|max:1',
            'isStartDate' => 'nullable|numeric|min:0|max:1',
            'isEndDate' => 'nullable|numeric|min:0|max:1',
            'workDuration' => 'nullable|numeric|min:0|max:1',
            'isGradeDuration' => 'nullable|numeric|min:0|max:1',
            'isPosition' => 'nullable|numeric|min:0|max:1',
            'isDatePosition' => 'nullable|numeric|min:0|max:1',
            'isEchelons' => 'nullable|numeric|min:0|max:1',
            'isEchelonDate' => 'nullable|numeric|min:0|max:1',
            'isPositionDescription' => 'nullable|numeric|min:0|max:1',
            'isGrade' => 'nullable|numeric|min:0|max:1',
            'isGradeDate' => 'nullable|numeric|min:0|max:1',
            'isAgency' => 'nullable|numeric|min:0|max:1',
            'isNoWorker' => 'nullable|numeric|min:0|max:1',
            'isKarisu' => 'nullable|numeric|min:0|max:1',
            'isNPWP' => 'nullable|numeric|min:0|max:1',
            'isEmployeeStatus' => 'nullable|numeric|min:0|max:1',
            'isNoFamily' => 'nullable|numeric|min:0|max:1',
            'isNIK' => 'nullable|numeric|min:0|max:1',
            'isCurrentAddress' => 'nullable|numeric|min:0|max:1',
            'isComplex' => 'nullable|numeric|min:0|max:1',
            'isHomeNumber' => 'nullable|numeric|min:0|max:1',
            'isPhoneNumber' => 'nullable|numeric|min:0|max:1',
            'isOfficeAddress' => 'nullable|numeric|min:0|max:1',
            'isOfficeNumber' => 'nullable|numeric|min:0|max:1',
            'isEmail' => 'nullable|numeric|min:0|max:1',
            'isOfficeEmail' => 'nullable|numeric|min:0|max:1',
            'isOrganization' => 'nullable|numeric|min:0|max:1',
            'isWorkUnit' => 'nullable|numeric|min:0|max:1',
            'isEmergencyContact' => 'nullable|numeric|min:0|max:1',
            'isPensionCap' => 'nullable|numeric|min:0|max:1',
            'isPositionHistory' => 'nullable|numeric|min:0|max:1',
            'isGradeHistory' => 'nullable|numeric|min:0|max:1',
            'isTrainingStructural' => 'nullable|numeric|min:0|max:1',
            'isTrainingFunctional' => 'nullable|numeric|min:0|max:1',
            'isTrainingTechnique' => 'nullable|numeric|min:0|max:1',
            'isSKP' => 'nullable|numeric|min:0|max:1',
            'isRecognition' => 'nullable|numeric|min:0|max:1',
            'isNotes' => 'nullable|numeric|min:0|max:1',
            'isEducationHistory' => 'nullable|numeric|min:0|max:1',
            'isDisciplinary' => 'nullable|numeric|min:0|max:1',
            'isFamilyHistory' => 'nullable|numeric|min:0|max:1',
            'isLeave' => 'nullable|numeric|min:0|max:1',
            'isAssessment' => 'nullable|numeric|min:0|max:1',
            'isCompetency' => 'nullable|numeric|min:0|max:1',
            'isTalentPool' => 'nullable|numeric|min:0|max:1',
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
            'extension.required' => 'harus di isi',
            'extension.in' => 'harus diantara xlsx, csv atau pdf',
            'deputy.array' => 'Deputy harus berupa array',
            'deputy.min' => 'Deputy harus memiliki minimal 1 item',
            'employee_type.array' => 'Employee Type harus berupa array',
            'employee_type.min' => 'Employee Type harus memiliki minimal 1 item',
            'echelons.array' => 'Echelons harus berupa array',
            'echelons.min' => 'Echelons harus memiliki minimal 1 item',
            'grades.array' => 'Grades harus berupa array',
            'grades.min' => 'Grades harus memiliki minimal 1 item',
            'position_status.array' => 'Position Status harus berupa array',
            'position_status.min' => 'Position Status harus memiliki minimal 1 item',
            'education.array' => 'Education harus berupa array',
            'education.min' => 'Education harus memiliki minimal 1 item',
            'gender.array' => 'Gender harus berupa array',
            'gender.min' => 'Gender harus memiliki minimal 1 item',
            'gender.max' => 'Gender tidak boleh lebih dari 2 item',
            'age_range.array' => 'Age Range harus berupa array',
            'age_range.min' => 'Age Range harus memiliki minimal 1 item',
            'marital_status.array' => 'Marital Status harus berupa array',
            'marital_status.min' => 'Marital Status harus memiliki minimal 1 item',
            'target_period.in' => 'Target Period harus diantara Q1, Q2, Q3, Q4, atau Tahunan',
            'target_year.date_format' => 'Target Year harus dalam format Y',
            'work_behavior_rating.numeric' => 'Work Behavior Rating harus berupa angka',
            'work_behavior_rating.in' => 'Work Behavior Rating harus diantara 1, 2, atau 3',
            'employee_performance_predicate.numeric' => 'Employee Performance Predicate harus berupa angka',
            'employee_performance_predicate.in' => 'Employee Performance Predicate harus diantara 1, 2, 3, 4, atau 5',
            'organizational_performance_achievement.numeric' => 'Organizational Performance Achievement harus berupa angka',
            'organizational_performance_achievement.in' => 'Organizational Performance Achievement harus diantara 1, 2, atau 3',
            'credit_period.numeric' => 'Credit Period harus berupa angka',
            'credit_period.in' => 'Credit Period harus diantara 1, 2, 3, 4, atau 5',
            'credit_year.date_format' => 'Credit Year harus dalam format Y',
            'isName.numeric' => 'IsName harus berupa angka',
            'isName.min' => 'IsName tidak boleh kurang dari 0',
            'isName.max' => 'IsName tidak boleh lebih dari 1',
            'isNip.numeric' => 'IsNIP harus berupa angka',
            'isNip.min' => 'IsNIP tidak boleh kurang dari 0',
            'isNip.max' => 'IsNIP tidak boleh lebih dari 1',
            'isBirthPlaceDate.numeric' => 'IsBirth Place Date harus berupa angka',
            'isBirthPlaceDate.min' => 'IsBirth Place Date tidak boleh kurang dari 0',
            'isBirthPlaceDate.max' => 'IsBirth Place Date tidak boleh lebih dari 1',
            'isAge.numeric' => 'IsAge harus berupa angka',
            'isAge.min' => 'IsAge tidak boleh kurang dari 0',
            'isAge.max' => 'IsAge tidak boleh lebih dari 1',
            'isReligion.numeric' => 'IsReligion harus berupa angka',
            'isReligion.min' => 'IsReligion tidak boleh kurang dari 0',
            'isReligion.max' => 'IsReligion tidak boleh lebih dari 1',
            'isGender.numeric' => 'IsGender harus berupa angka',
            'isGender.min' => 'IsGender tidak boleh kurang dari 0',
            'isGender.max' => 'IsGender tidak boleh lebih dari 1',
            'isMaritalStatus.numeric' => 'IsMaritalStatus harus berupa angka',
            'isMaritalStatus.min' => 'IsMaritalStatus tidak boleh kurang dari 0',
            'isMaritalStatus.max' => 'IsMaritalStatus tidak boleh lebih dari 1',
            'isEmployeeType.numeric' => 'IsEmployeeType harus berupa angka',
            'isEmployeeType.min' => 'IsEmployeeType tidak boleh kurang dari 0',
            'isEmployeeType.max' => 'IsEmployeeType tidak boleh lebih dari 1',
            'isAssistanceType.numeric' => 'IsAssistanceType harus berupa angka',
            'isAssistanceType.min' => 'IsAssistanceType tidak boleh kurang dari 0',
            'isAssistanceType.max' => 'IsAssistanceType tidak boleh lebih dari 1',
            'isOutsourcingType.numeric' => 'IsOutsourcingType harus berupa angka',
            'isOutsourcingType.min' => 'IsOutsourcingType tidak boleh kurang dari 0',
            'isOutsourcingType.max' => 'IsOutsourcingType tidak boleh lebih dari 1',
            'isDateCPNS.numeric' => 'IsDateCPNS harus berupa angka',
            'isDateCPNS.min' => 'IsDateCPNS tidak boleh kurang dari 0',
            'isDateCPNS.max' => 'IsDateCPNS tidak boleh lebih dari 1',
            'isStartDate.numeric' => 'IsStartDate harus berupa angka',
            'isStartDate.min' => 'IsStartDate tidak boleh kurang dari 0',
            'isStartDate.max' => 'IsStartDate tidak boleh lebih dari 1',
            'isEndDate.numeric' => 'IsEndDate harus berupa angka',
            'isEndDate.min' => 'IsEndDate tidak boleh kurang dari 0',
            'isEndDate.max' => 'IsEndDate tidak boleh lebih dari 1',
            'workDuration.numeric' => 'WorkDuration harus berupa angka',
            'workDuration.min' => 'WorkDuration tidak boleh kurang dari 0',
            'workDuration.max' => 'WorkDuration tidak boleh lebih dari 1',
            'isGradeDuration.numeric' => 'IsGradeDuration harus berupa angka',
            'isGradeDuration.min' => 'IsGradeDuration tidak boleh kurang dari 0',
            'isGradeDuration.max' => 'IsGradeDuration tidak boleh lebih dari 1',
            'isPosition.numeric' => 'IsPosition harus berupa angka',
            'isPosition.min' => 'IsPosition tidak boleh kurang dari 0',
            'isPosition.max' => 'IsPosition tidak boleh lebih dari 1',
            'isDatePosition.numeric' => 'IsDatePosition harus berupa angka',
            'isDatePosition.min' => 'IsDatePosition tidak boleh kurang dari 0',
            'isDatePosition.max' => 'IsDatePosition tidak boleh lebih dari 1',
            'isEchelons.numeric' => 'IsEchelons harus berupa angka',
            'isEchelons.min' => 'IsEchelons tidak boleh kurang dari 0',
            'isEchelons.max' => 'IsEchelons tidak boleh lebih dari 1',
            'isEchelonDate.numeric' => 'IsEchelonDate harus berupa angka',
            'isEchelonDate.min' => 'IsEchelonDate tidak boleh kurang dari 0',
            'isEchelonDate.max' => 'IsEchelonDate tidak boleh lebih dari 1',
            'isPositionDescription.numeric' => 'IsPositionDescription harus berupa angka',
            'isPositionDescription.min' => 'IsPositionDescription tidak boleh kurang dari 0',
            'isPositionDescription.max' => 'IsPositionDescription tidak boleh lebih dari 1',
            'isGrade.numeric' => 'IsGrade harus berupa angka',
            'isGrade.min' => 'IsGrade tidak boleh kurang dari 0',
            'isGrade.max' => 'IsGrade tidak boleh lebih dari 1',
            'isGradeDate.numeric' => 'IsGradeDate harus berupa angka',
            'isGradeDate.min' => 'IsGradeDate tidak boleh kurang dari 0',
            'isGradeDate.max' => 'IsGradeDate tidak boleh lebih dari 1',
            'isAgency.numeric' => 'IsAgency harus berupa angka',
            'isAgency.min' => 'IsAgency tidak boleh kurang dari 0',
            'isAgency.max' => 'IsAgency tidak boleh lebih dari 1',
            'isNoWorker.numeric' => 'IsNoWorker harus berupa angka',
            'isNoWorker.min' => 'IsNoWorker tidak boleh kurang dari 0',
            'isNoWorker.max' => 'IsNoWorker tidak boleh lebih dari 1',
            'isKarisu.numeric' => 'IsKarisu harus berupa angka',
            'isKarisu.min' => 'IsKarisu tidak boleh kurang dari 0',
            'isKarisu.max' => 'IsKarisu tidak boleh lebih dari 1',
            'isNPWP.numeric' => 'IsNPWP harus berupa angka',
            'isNPWP.min' => 'IsNPWP tidak boleh kurang dari 0',
            'isNPWP.max' => 'IsNPWP tidak boleh lebih dari 1',
            'isEmployeeStatus.numeric' => 'IsEmployeeStatus harus berupa angka',
            'isEmployeeStatus.min' => 'IsEmployeeStatus tidak boleh kurang dari 0',
            'isEmployeeStatus.max' => 'IsEmployeeStatus tidak boleh lebih dari 1',
            'isNoFamily.numeric' => 'IsNoFamily harus berupa angka',
            'isNoFamily.min' => 'IsNoFamily tidak boleh kurang dari 0',
            'isNoFamily.max' => 'IsNoFamily tidak boleh lebih dari 1',
            'isNIK.numeric' => 'IsNIK harus berupa angka',
            'isNIK.min' => 'IsNIK tidak boleh kurang dari 0',
            'isNIK.max' => 'IsNIK tidak boleh lebih dari 1',
            'isCurrentAddress.numeric' => 'IsCurrentAddress harus berupa angka',
            'isCurrentAddress.min' => 'IsCurrentAddress tidak boleh kurang dari 0',
            'isCurrentAddress.max' => 'IsCurrentAddress tidak boleh lebih dari 1',
            'isComplex.numeric' => 'IsComplex harus berupa angka',
            'isComplex.min' => 'IsComplex tidak boleh kurang dari 0',
            'isComplex.max' => 'IsComplex tidak boleh lebih dari 1',
            'isHomeNumber.numeric' => 'IsHomeNumber harus berupa angka',
            'isHomeNumber.min' => 'IsHomeNumber tidak boleh kurang dari 0',
            'isHomeNumber.max' => 'IsHomeNumber tidak boleh lebih dari 1',
            'isPhoneNumber.numeric' => 'IsPhoneNumber harus berupa angka',
            'isPhoneNumber.min' => 'IsPhoneNumber tidak boleh kurang dari 0',
            'isPhoneNumber.max' => 'IsPhoneNumber tidak boleh lebih dari 1',
            'isOfficeAddress.numeric' => 'IsOfficeAddress harus berupa angka',
            'isOfficeAddress.min' => 'IsOfficeAddress tidak boleh kurang dari 0',
            'isOfficeAddress.max' => 'IsOfficeAddress tidak boleh lebih dari 1',
            'isOfficeNumber.numeric' => 'IsOfficeNumber harus berupa angka',
            'isOfficeNumber.min' => 'IsOfficeNumber tidak boleh kurang dari 0',
            'isOfficeNumber.max' => 'IsOfficeNumber tidak boleh lebih dari 1',
            'isEmail.numeric' => 'IsEmail harus berupa angka',
            'isEmail.min' => 'IsEmail tidak boleh kurang dari 0',
            'isEmail.max' => 'IsEmail tidak boleh lebih dari 1',
            'isOfficeEmail.numeric' => 'IsOfficeEmail harus berupa angka',
            'isOfficeEmail.min' => 'IsOfficeEmail tidak boleh kurang dari 0',
            'isOfficeEmail.max' => 'IsOfficeEmail tidak boleh lebih dari 1',
            'isOrganization.numeric' => 'IsOrganization harus berupa angka',
            'isOrganization.min' => 'IsOrganization tidak boleh kurang dari 0',
            'isOrganization.max' => 'IsOrganization tidak boleh lebih dari 1',
            'isWorkUnit.numeric' => 'IsWorkUnit harus berupa angka',
            'isWorkUnit.min' => 'IsWorkUnit tidak boleh kurang dari 0',
            'isWorkUnit.max' => 'IsWorkUnit tidak boleh lebih dari 1',
            'isEmergencyContact.numeric' => 'IsEmergencyContact harus berupa angka',
            'isEmergencyContact.min' => 'IsEmergencyContact tidak boleh kurang dari 0',
            'isEmergencyContact.max' => 'IsEmergencyContact tidak boleh lebih dari 1',
            'isPensionCap.numeric' => 'IsPensionCap harus berupa angka',
            'isPensionCap.min' => 'IsPensionCap tidak boleh kurang dari 0',
            'isPensionCap.max' => 'IsPensionCap tidak boleh lebih dari 1',
            'isPositionHistory.numeric' => 'IsPositionHistory harus berupa angka',
            'isPositionHistory.min' => 'IsPositionHistory tidak boleh kurang dari 0',
            'isPositionHistory.max' => 'IsPositionHistory tidak boleh lebih dari 1',
            'isGradeHistory.numeric' => 'IsGradeHistory harus berupa angka',
            'isGradeHistory.min' => 'IsGradeHistory tidak boleh kurang dari 0',
            'isGradeHistory.max' => 'IsGradeHistory tidak boleh lebih dari 1',
            'isTrainingStructural.numeric' => 'IsTrainingStructural harus berupa angka',
            'isTrainingStructural.min' => 'IsTrainingStructural tidak boleh kurang dari 0',
            'isTrainingStructural.max' => 'IsTrainingStructural tidak boleh lebih dari 1',
            'isTrainingFunctional.numeric' => 'IsTrainingFunctional harus berupa angka',
            'isTrainingFunctional.min' => 'IsTrainingFunctional tidak boleh kurang dari 0',
            'isTrainingFunctional.max' => 'IsTrainingFunctional tidak boleh lebih dari 1',
            'isTrainingTechnique.numeric' => 'IsTrainingTechnique harus berupa angka',
            'isTrainingTechnique.min' => 'IsTrainingTechnique tidak boleh kurang dari 0',
            'isTrainingTechnique.max' => 'IsTrainingTechnique tidak boleh lebih dari 1',
            'isSKP.numeric' => 'IsSKP harus berupa angka',
            'isSKP.min' => 'IsSKP tidak boleh kurang dari 0',
            'isSKP.max' => 'IsSKP tidak boleh lebih dari 1',
            'isRecognition.numeric' => 'IsRecognition harus berupa angka',
            'isRecognition.min' => 'IsRecognition tidak boleh kurang dari 0',
            'isRecognition.max' => 'IsRecognition tidak boleh lebih dari 1',
            'isNotes.numeric' => 'IsNotes harus berupa angka',
            'isNotes.min' => 'IsNotes tidak boleh kurang dari 0',
            'isNotes.max' => 'IsNotes tidak boleh lebih dari 1',
            'isEducationHistory.numeric' => 'IsEducationHistory harus berupa angka',
            'isEducationHistory.min' => 'IsEducationHistory tidak boleh kurang dari 0',
            'isEducationHistory.max' => 'IsEducationHistory tidak boleh lebih dari 1',
            'isDisciplinary.numeric' => 'IsDisciplinary harus berupa angka',
            'isDisciplinary.min' => 'IsDisciplinary tidak boleh kurang dari 0',
            'isDisciplinary.max' => 'IsDisciplinary tidak boleh lebih dari 1',
            'isFamilyHistory.numeric' => 'IsFamilyHistory harus berupa angka',
            'isFamilyHistory.min' => 'IsFamilyHistory tidak boleh kurang dari 0',
            'isFamilyHistory.max' => 'IsFamilyHistory tidak boleh lebih dari 1',
            'isLeave.numeric' => 'IsLeave harus berupa angka',
            'isLeave.min' => 'IsLeave tidak boleh kurang dari 0',
            'isLeave.max' => 'IsLeave tidak boleh lebih dari 1',
            'isAssessment.numeric' => 'IsAssessment harus berupa angka',
            'isAssessment.min' => 'IsAssessment tidak boleh kurang dari 0',
            'isAssessment.max' => 'IsAssessment tidak boleh lebih dari 1',
            'isCompetency.numeric' => 'IsCompetency harus berupa angka',
            'isCompetency.min' => 'IsCompetency tidak boleh kurang dari 0',
            'isCompetency.max' => 'IsCompetency tidak boleh lebih dari 1',
            'isTalentPool.numeric' => 'IsTalentPool harus berupa angka',
            'isTalentPool.min' => 'IsTalentPool tidak boleh kurang dari 0',
            'isTalentPool.max' => 'IsTalentPool tidak boleh lebih dari 1',
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
            'extension' => [
                'description' => 'Refers to file type to export',
                'example' => 'csv',
            ],
            'organization' => [
                'description' => 'Refers to IDs of Organization',
                'example' => 1,
            ],
            'employee_type' => [
                'description' => 'Refers to IDs of type of employee (1: ASN, 2: Non ASN, 3: Outsourcing)',
                'example' => 1,
            ],
            'echelons' => [
                'description' => 'Refers to IDs of employee echelons',
                'example' => 1,
            ],
            'grades' => [
                'description' => 'Refers to IDs of employee grades',
                'example' => 1,
            ],
            'position_status' => [
                'description' => 'Refers to IDs of employee position status',
                'example' => 1,
            ],
            'education' => [
                'description' => 'Refers to type of employee education (1=SD/Sederajat, 2=SLTP/Sederajat, 3=SLTA/Sederajat,
                 4=Akademik/D3/S.Muda, 5=Diploma IV, 6=Strata I, 7=Strata II, 8=Strata III )',
                'example' => 1,
            ],
            'gender' => [
                'description' => 'Refers to gender of employee (1 : Laki - Laki, 0 : Perempuan)',
                'example' => 1,
            ],
            'marital_status' => [
                'description' => 'Refers to marital status of employee (1=Belum Menikah, 2=Menikah, 3=Cerai Hidup, 4=Cerai Mati)',
                'example' => 1,
            ],
            'age_range' => [
                'description' => 'Refers to age range of employee',
                'example' => 1,
            ],
            'target_period' => [
                'description' => 'Refers to employees Target appraisal period',
                'example' => 'Q1',
            ],
            'target_year' => [
                'description' => 'Refers to employees Target year period',
                'example' => '2024',
            ],
            'work_behavior_rating' => [
                'description' => 'Refers to employees work behavior rating',
                'example' => '2',
            ],
            'employee_performance_predicate' => [
                'description' => 'Refers to employees performance predicate',
                'example' => '3',
            ],
            'organizational_performance_achievement' => [
                'description' => 'Refers to employees organizational performance achievement',
                'example' => '1',
            ],
            'credit_period' => [
                'description' => 'Refers to employees credit period',
                'example' => '1',
            ],
            'credit_year' => [
                'description' => 'Refers to employees credit year period',
                'example' => '2024',
            ],
            'isName' => [
                'description' => 'Indicates whether the name field is included in the request',
                'example' => [0, 1]
            ],
            'isPosition' => [
                'description' => 'Indicates whether the position field is included in the request',
                'example' => [0, 1]
            ],
            'isPositionDescription' => [
                'description' => 'Indicates whether the position description field is included in the request',
                'example' => [0, 1]
            ],
            'isEchelons' => [
                'description' => 'Indicates whether the echelons field is included in the request',
                'example' => [0, 1]
            ],
            'isGrade' => [
                'description' => 'Indicates whether the grade field is included in the request',
                'example' => [0, 1]
            ],
            'isNip' => [
                'description' => 'Indicates whether the NIP (National Identification Number) field is included in the request',
                'example' => [0, 1]
            ],
            'isBirthPlaceDate' => [
                'description' => 'Indicates whether the birth place and date field is included in the request',
                'example' => [0, 1]
            ],
            'isAge' => [
                'description' => 'Indicates whether the age field is included in the request',
                'example' => [0, 1]
            ],
            'isReligion' => [
                'description' => 'Indicates whether the religion field is included in the request',
                'example' => [0, 1]
            ],
            'isGender' => [
                'description' => 'Indicates whether the gender field is included in the request',
                'example' => [0, 1]
            ],
            'isMaritalStatus' => [
                'description' => 'Indicates whether the marital status field is included in the request',
                'example' => [0, 1]
            ],
            'isAgency' => [
                'description' => 'Indicates whether the agency field is included in the request',
                'example' => [0, 1]
            ],
            'isOrganization' => [
                'description' => 'Indicates whether the organization field is included in the request',
                'example' => [0, 1]
            ],
            'isWorkUnit' => [
                'description' => 'Indicates whether the work unit field is included in the request',
                'example' => [0, 1]
            ],
            'isNoWorker' => [
                'description' => 'Indicates whether the worker number field is included in the request',
                'example' => [0, 1]
            ],
            'workDuration' => [
                'description' => 'Indicates the duration of work',
                'example' => [0, 1]
            ],
            'isGradeDuration' => [
                'description' => 'Indicates whether the grade duration field is included in the request',
                'example' => [0, 1]
            ],
            'isNPWP' => [
                'description' => 'Indicates whether the NPWP (Tax Identification Number) field is included in the request',
                'example' => [0, 1]
            ],
            'isEmployeeStatus' => [
                'description' => 'Indicates whether the employee status field is included in the request',
                'example' => [0, 1]
            ],
            'isCurrentAddress' => [
                'description' => 'Indicates whether the current address field is included in the request',
                'example' => [0, 1]
            ],
            'isComplex' => [
                'description' => 'Indicates whether the complex field is included in the request',
                'example' => [0, 1]
            ],
            'isHomeNumber' => [
                'description' => 'Indicates whether the home number field is included in the request',
                'example' => [0, 1]
            ],
            'isPhoneNumber' => [
                'description' => 'Indicates whether the phone number field is included in the request',
                'example' => [0, 1]
            ],
            'isOfficeAddress' => [
                'description' => 'Indicates whether the office address field is included in the request',
                'example' => [0, 1]
            ],
            'isOfficeNumber' => [
                'description' => 'Indicates whether the office number field is included in the request',
                'example' => [0, 1]
            ],
            'isEmail' => [
                'description' => 'Indicates whether the email field is included in the request',
                'example' => [0, 1]
            ],
            'isPensionCap' => [
                'description' => 'Indicates whether the pension cap field is included in the request',
                'example' => [0, 1]
            ],
            'isPositionHistory' => [
                'description' => 'Indicates whether the position history field is included in the request',
                'example' => [0, 1]
            ],
            'isGradeHistory' => [
                'description' => 'Indicates whether the grade history field is included in the request',
                'example' => [0, 1]
            ],
            'isTrainingStructural' => [
                'description' => 'Indicates whether the structural training field is included in the request',
                'example' => [0, 1]
            ],
            'isTrainingFunctional' => [
                'description' => 'Indicates whether the functional training field is included in the request',
                'example' => [0, 1]
            ],
            'isTrainingTechnique' => [
                'description' => 'Indicates whether the technique training field is included in the request',
                'example' => [0, 1]
            ],
            'isSKP' => [
                'description' => 'Indicates whether the SKP (Employee Performance Target) field is included in the request',
                'example' => [0, 1]
            ],
            'isRecognition' => [
                'description' => 'Indicates whether the recognition field is included in the request',
                'example' => [0, 1]
            ],
            'isNotes' => [
                'description' => 'Indicates whether the notes field is included in the request',
                'example' => [0, 1]
            ],
            'isEducationHistory' => [
                'description' => 'Indicates whether the education history field is included in the request',
                'example' => [0, 1]
            ],
            'isDisciplinary' => [
                'description' => 'Indicates whether the disciplinary field is included in the request',
                'example' => [0, 1]
            ],
            'isFamilyHistory' => [
                'description' => 'Indicates whether the family history field is included in the request',
                'example' => [0, 1]
            ],
            'isLeave' => [
                'description' => 'Indicates whether the leave field is included in the request',
                'example' => [0, 1]
            ],
            'isAssessment' => [
                'description' => 'Indicates whether the assessment field is included in the request',
                'example' => [0, 1]
            ],
            'isCompetency' => [
                'description' => 'Indicates whether the competency field is included in the request',
                'example' => [0, 1]
            ],
            'isTalentPool' => [
                'description' => 'Indicates whether the talent pool field is included in the request',
                'example' => [0, 1]
            ],
            'deputy' => [
                'description' => 'deputy int[] List of employees deputy. Example: [1,2]',
                'example' => [1],
            ],
            'isEmployeeType' => [
                'description' => 'Indicates whether the employee type field is included in the output document',
                'example' => 1,
            ],
            'isAssistanceType' => [
                'description' => 'Indicates whether the employee type assistance  field is included in the output document. Example: 1',
                'example' => 1,
            ],
            'isOutsourcingType' => [
                'description' => 'Indicates whether the employee type outsourcing  field is included in the output document. Example: 1',
                'example' => 1,
            ],
            'isDateCPNS' => [
                'description' => 'Indicates whether the CPNS Start date field is included in the request.',
                'example' => 1,
            ],
            'isStartDate' => [
                'description' => 'Indicates whether the employment start date field is included in the request.',
                'example' => 1,
            ],
            'isEndDate' => [
                'description' => 'Indicates whether the employment end date field is included in the request.',
                'example' => 1,
            ],
            'isDatePosition' => [
                'description' => 'Indicates whether the position start date field is included in the request.',
                'example' => 1,
            ],
            'isEchelonDate' => [
                'description' => 'Indicates whether the echelon start date field is included in the request.',
                'example' => 1,
            ],
            'isGradeDate' => [
                'description' => 'Indicates whether the grade start date field is included in the request.',
                'example' => 1,
            ],
            'isKarisu' => [
                'description' => 'Indicates whether the Number Karisu field is included in the request.',
                'example' => 1,
            ],
            'isNoFamily' => [
                'description' => 'Indicates whether the Number family field is included in the request.',
                'example' => 1,
            ],
            'isNIK' => [
                'description' => 'Indicates whether the NIK field is included in the request.',
                'example' => 1,
            ],
            'isOfficeEmail' => [
                'description' => 'Indicates whether the office email field is included in the request.',
                'example' => 1,
            ],
            'isEmergencyContact' => [
                'description' => 'Indicates whether the emergency contact field is included in the request.',
                'example' => 1,
            ],

        ];
    }
}
