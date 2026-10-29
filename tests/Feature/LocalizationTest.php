<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class LocalizationTest extends TestCase
{
    public function test_application_locale_is_configured_to_spanish(): void
    {
        $this->assertEquals('es', config('app.locale'));
        $this->assertEquals('es', config('app.fallback_locale'));
        $this->assertEquals('es', app()->getLocale());
    }

    public function test_auth_messages_are_translated_to_spanish(): void
    {
        $this->assertEquals(
            'Estas credenciales no coinciden con nuestros registros.',
            __('auth.failed')
        );

        $this->assertEquals(
            'Demasiados intentos de acceso fallidos. Por favor intenta de nuevo en 45 segundos.',
            __('auth.throttle', ['seconds' => 45])
        );

        $this->assertEquals(
            'La contraseña ingresada es incorrecta.',
            __('auth.password')
        );
    }

    public function test_validation_errors_use_spanish_rules_and_attribute_names(): void
    {
        $validator = Validator::make(
            [],
            [
                'email' => 'required',
                'password' => 'required',
                'copropiedad_id' => 'required',
            ]
        );

        $this->assertTrue($validator->fails());
        $errors = $validator->errors();

        $this->assertEquals(
            'El campo correo electrónico es obligatorio.',
            $errors->first('email')
        );

        $this->assertEquals(
            'El campo contraseña es obligatorio.',
            $errors->first('password')
        );

        $this->assertEquals(
            'El campo conjunto residencial / copropiedad es obligatorio.',
            $errors->first('copropiedad_id')
        );
    }

    public function test_email_format_validation_in_spanish(): void
    {
        $validator = Validator::make(
            ['email' => 'no-es-correo'],
            ['email' => 'email']
        );

        $this->assertTrue($validator->fails());
        $this->assertEquals(
            'El campo correo electrónico debe ser una dirección de correo electrónico válida.',
            $validator->errors()->first('email')
        );
    }

    public function test_passwords_broker_messages_are_in_spanish(): void
    {
        $this->assertEquals(
            '¡Te hemos enviado por correo el enlace para restablecer tu contraseña!',
            __('passwords.sent')
        );

        $this->assertEquals(
            'Este enlace para restablecer contraseña no es válido o ya expiró.',
            __('passwords.token')
        );

        $this->assertEquals(
            'No encontramos ningún usuario registrado con esa dirección de correo electrónico.',
            __('passwords.user')
        );
    }

    public function test_core_notification_strings_are_translated_via_json(): void
    {
        $this->assertEquals(
            'Notificación para restablecer contraseña',
            __('Reset Password Notification')
        );

        $this->assertEquals(
            'Restablecer contraseña',
            __('Reset Password')
        );
    }
}
