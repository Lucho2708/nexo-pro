<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Modules\Property\Models\ZonaComun;
use App\Modules\Property\Models\Unidad;

class StoreReservaRequest extends FormRequest
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
            'zona_id' => [
                'required',
                Rule::exists(ZonaComun::class, 'id'),
                function ($attribute, $value, $fail) use ($user) {
                    $existsInTenant = ZonaComun::where('id', $value)
                        ->where('copropiedad_id', $user->current_copropiedad_id)
                        ->exists();
                    if (!$existsInTenant) {
                        $fail('La zona común seleccionada no pertenece a la copropiedad actual.');
                    }
                }
            ],
            'unidad_id' => [
                'required',
                Rule::exists(Unidad::class, 'id'),
                function ($attribute, $value, $fail) use ($user) {
                    if (!$user->unidades()->where('property.unidades.id', $value)->exists()) {
                        $fail('La unidad seleccionada no le pertenece.');
                    }
                }
            ],
            'fecha' => 'required|date|after_or_equal:today',
            'hora_inicio' => 'required',
            'hora_fin' => 'required|after:hora_inicio',
            'cantidad_personas' => 'required|integer|min:1',
        ];
    }
}
