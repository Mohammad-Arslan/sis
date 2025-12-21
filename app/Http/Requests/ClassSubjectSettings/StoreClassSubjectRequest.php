<?php

namespace App\Http\Requests\ClassSubjectSettings;

use Illuminate\Foundation\Http\FormRequest;

class StoreClassSubjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'class_id' => 'required|exists:com_classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'branch_id' => 'nullable|exists:branches,id',
            'state_id' => 'nullable|exists:states,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'class_id.required' => 'Class is required.',
            'class_id.exists' => 'The selected class is invalid.',
            'subject_id.required' => 'Subject is required.',
            'subject_id.exists' => 'The selected subject is invalid.',
            'branch_id.exists' => 'The selected branch is invalid.',
            'state_id.exists' => 'The selected state is invalid.',
        ];
    }
}
