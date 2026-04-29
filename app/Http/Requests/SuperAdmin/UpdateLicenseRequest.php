<?php

namespace App\Http\Requests\SuperAdmin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLicenseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isSuperAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'payments_enabled'  => 'boolean',
            'can_charge_online' => 'boolean',
            'pqrs_enabled'      => 'boolean',
            'reservas_enabled'  => 'boolean',
            'asamblea_virtual_enabled' => 'boolean',
            'plan'              => 'string|in:basic,pro,enterprise',
            'license_status'    => 'string|in:active,suspended,expired',
            'license_expires_at' => 'nullable|date',
        ];
    }
}
