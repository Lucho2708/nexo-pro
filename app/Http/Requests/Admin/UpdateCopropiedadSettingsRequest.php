<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCopropiedadSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'settings' => 'required|array',
            'settings.payments_enabled' => 'boolean',
            'settings.wompi_public_key' => 'nullable|string',
            'settings.wompi_private_key' => 'nullable|string',
            'settings.wompi_integrity_key' => 'nullable|string',
            'settings.wompi_webhook_secret' => 'nullable|string',
            'settings.gateways' => 'nullable|array',
            'settings.gateways.*.enabled' => 'boolean',
            'settings.gateways.*.url' => 'nullable|url',
            'area_construida_total' => 'nullable|numeric|min:0',
        ];
    }
}
