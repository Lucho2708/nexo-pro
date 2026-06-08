<?php

namespace App\Modules\IAM\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOnboardingRequest extends FormRequest
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
            // Property Info
            'nit' => 'required|string|unique:copropiedades,nit',
            'nombre_copropiedad' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'ciudad' => 'required|string|max:255',
            
            // Operation
            'plan' => 'required|string|in:basic,pro,enterprise,standalone',
            'unidades_totales' => 'required|integer|min:0',
            'torres' => 'required|integer|min:0',
            'is_standalone' => 'nullable|boolean',
            
            // Admin Account
            'name' => 'required|string|max:255',
            'email' => [
                'required', 
                'string', 
                'email', 
                'max:255', 
                \Illuminate\Validation\Rule::unique(\App\Modules\IAM\Models\User::class, 'email')
            ],
            'password' => 'required|string|min:8',
            'terms' => 'accepted'
        ];
    }
}
