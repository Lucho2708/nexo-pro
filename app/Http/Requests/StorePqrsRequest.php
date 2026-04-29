<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePqrsRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = $this->user();
        
        return [
            'unidad_id' => [
                'required', 
                'exists:unidades,id',
                function ($attribute, $value, $fail) use ($user) {
                    if (!$user->unidades()->where('unidades.id', $value)->exists()) {
                        $fail('La unidad seleccionada no le pertenece o no es válida en su contexto.');
                    }
                }
            ],
            'tipo' => 'required|string|in:peticion,queja,reclamo,sugerencia,reporte_danos,felicitaciones',
            'asunto' => 'required|string|max:255',
            'mensaje' => 'required|string',
            'prioridad' => 'required|string|in:baja,media,alta',
            'adjuntos.*' => 'nullable|file|mimetypes:image/jpeg,image/png,application/pdf|extensions:jpg,jpeg,png,pdf|max:2048',
        ];
    }
}
