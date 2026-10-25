<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * [LÓGICA DE NEGOCIO / UX]
 * Valida la entrada del usuario en el formulario de contacto/ROI garantizando datos de enlace verídicos.
 * Protege contra spam silenciosamente mediante honeypot anti-bot.
 *
 * [ASPECTO TÉCNICO / UI]
 * FormRequest con sanitización previa y detección de trampas honeypot ('website_hp')
 * para mitigar ataques automatizados de scraping y flood sin fricción de Captcha (OWASP A04/A07).
 */
class StoreLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Normaliza nombres de campo para soportar indistintamente la nomenclatura nueva y la legacy.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'nombre' => $this->input('nombre') ?? $this->input('fullName'),
            'telefono' => $this->input('telefono') ?? $this->input('phone'),
            'copropiedad_nombre' => $this->input('copropiedad_nombre') ?? $this->input('buildingName'),
            'website_hp' => $this->input('website_hp') ?? $this->input('honeypot'),
        ]);
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'min:3', 'max:120'],
            'email' => ['required', 'string', 'email', 'max:150'],
            'telefono' => ['required', 'string', 'min:7', 'max:25'],
            'copropiedad_nombre' => ['required', 'string', 'min:3', 'max:150'],
            'unidades_totales' => ['nullable', 'integer', 'min:1', 'max:10000'],
            'cuota_promedio_mensual' => ['nullable', 'numeric', 'min:0'],
            'rol' => ['nullable', 'string', 'max:50'],
            'website_hp' => ['nullable', 'string', 'max:100'], // Campo honeypot anti-spam
            'metadata' => ['nullable', 'array'],
        ];
    }

    /**
     * Determina si la petición fue completada por un robot automático que llenó el campo trampa.
     */
    public function isSpamBot(): bool
    {
        $honeypot = $this->input('website_hp');
        return !empty($honeypot);
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre completo es obligatorio.',
            'email.required' => 'El correo electrónico corporativo es obligatorio.',
            'email.email' => 'Ingrese una dirección de correo válida.',
            'telefono.required' => 'El teléfono de contacto es indispensable para agendar la llamada.',
            'copropiedad_nombre.required' => 'Por favor indique el nombre de la copropiedad o condominio.',
        ];
    }
}
