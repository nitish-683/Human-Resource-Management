<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCandidateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = auth()->user();
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            return $user->can('candidate_edit');
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $candidateid = $this->route('candidate'); 
        return [
            'name' => 'required|regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/|max:255',
            'email' => 'required|email|unique:candidates,email,' . $candidateid . '|max:255',
            'phone' => 'required|regex:/^[0-9]{10,13}$/|unique:candidates,phone,' . $candidateid . '|max:13', 
            'password' => 'nullable|string|min:8',
        ];
    }
}
