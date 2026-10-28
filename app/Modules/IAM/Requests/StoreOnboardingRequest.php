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
            'nit' => [
                'required',
                'string',
                \Illuminate\Validation\Rule::unique(\App\Modules\Property\Models\Copropiedad::class, 'nit'),
            ],
            'nombre_copropiedad' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'departamento' => 'required|string|max:100',
            'ciudad' => 'required|string|max:255',
            
            // Operation & Subscription
            'plan' => 'required|string|in:boutique,condominio_pro,macro_conjunto,basic,pro,enterprise,standalone',
            'billing_cycle' => 'nullable|string|in:monthly,annual',
            'unidades_totales' => 'nullable|integer|min:0',
            'torres' => 'nullable|integer|min:0',
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

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'terms.accepted' => 'Debes autorizar el tratamiento de datos y aceptar los Términos del Servicio para continuar.',
            'nit.required' => 'El NIT de la copropiedad es obligatorio.',
            'nit.unique' => 'Ya existe una copropiedad registrada con este NIT.',
            'nombre_copropiedad.required' => 'El nombre del conjunto o edificio es obligatorio.',
            'direccion.required' => 'La dirección física es obligatoria.',
            'departamento.required' => 'El departamento es obligatorio.',
            'ciudad.required' => 'El municipio o ciudad es obligatorio.',
            'name.required' => 'El nombre del administrador es obligatorio.',
            'email.required' => 'El correo electrónico corporativo es obligatorio.',
            'email.email' => 'Debes ingresar un correo electrónico válido.',
            'email.unique' => 'Ya existe una cuenta registrada con este correo electrónico.',
            'password.required' => 'La contraseña de acceso es obligatoria.',
            'password.min' => 'La contraseña debe contener al menos 8 caracteres.',
            'plan.required' => 'Debes seleccionar un plan de suscripción.',
        ];
    }
}
