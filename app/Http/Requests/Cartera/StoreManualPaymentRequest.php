<?php

namespace App\Http\Requests\Cartera;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Modules\Property\Models\Unidad;
use App\Modules\Finance\Models\ConceptoCobro;

class StoreManualPaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Autenticación centralizada vía Middleware
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'unidad_id' => ['required', Rule::exists(Unidad::class, 'id')],
            'concepto_id' => ['required', Rule::exists(ConceptoCobro::class, 'id')],
            'monto' => 'required|numeric|min:0',
            'fecha' => 'required|date',
            'referencia' => 'nullable|string',
            'soporte' => 'nullable|file|mimetypes:image/jpeg,image/png,application/pdf|extensions:jpg,jpeg,png,pdf|max:5120',
        ];
    }
}