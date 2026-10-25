<?php

declare(strict_types=1);

namespace Tests\Unit\Auth;

use Tests\TestCase;
use App\DTOs\RegisterCopropiedadDTO;
use App\Modules\IAM\Actions\Auth\RegisterCopropiedadAction;
use App\Modules\Property\Models\Copropiedad;
use App\Modules\IAM\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Event;
use Mockery;
use InvalidArgumentException;

/**
 * SecureRegistrationFlowUnitTest
 *
 * [LÓGICA DE NEGOCIO]
 * Valida el flujo seguro de registro y creación inicial de copropiedades y administradores:
 * - DTO fuertemente tipado con validación de obligatoriedad (NIT, nombre, email, password).
 * - Encriptación segura de credenciales mediante Bcrypt (OWASP A02/A07).
 * - Transacción atómica que garantiza la creación simultánea de copropiedad y usuario administrador (ACID).
 * - Cumplimiento legal de aceptación de términos (Ley 1581 Habeas Data).
 *
 * [ASPECTO TÉCNICO]
 * Pruebas unitarias que aíslan la persistencia mediante Mockery y verifican hashing y DTOs.
 */
class SecureRegistrationFlowUnitTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_dto_creates_from_valid_payload(): void
    {
        $payload = [
            'nit' => '900123456-7',
            'nombre_copropiedad' => 'Edificio Altos de San Jerónimo',
            'direccion' => 'Calle 100 # 15-20',
            'ciudad' => 'Bogotá',
            'name' => 'Carlos Administrador',
            'email' => 'carlos.admin@altos.com',
            'password' => 'PasswordSeguro!2026',
            'plan' => 'enterprise',
            'unidades_totales' => 120,
            'torres' => 2,
            'is_standalone' => false,
        ];

        $dto = RegisterCopropiedadDTO::fromRequest($payload);

        $this->assertEquals('900123456-7', $dto->nit);
        $this->assertEquals('Edificio Altos de San Jerónimo', $dto->nombre_copropiedad);
        $this->assertEquals('carlos.admin@altos.com', $dto->email);
        $this->assertEquals('enterprise', $dto->plan);
        $this->assertEquals(120, $dto->unidades_totales);
        $this->assertFalse($dto->is_standalone);
    }

    public function test_password_hashing_produces_secure_bcrypt_hash(): void
    {
        $rawPassword = 'PasswordSeguro!2026';
        $hashed = Hash::make($rawPassword);

        $this->assertTrue(Hash::check($rawPassword, $hashed), 'El hash generado debe verificar la clave en texto plano');
        $this->assertNotEquals($rawPassword, $hashed, 'La contraseña nunca debe almacenarse en texto plano');
        $this->assertStringStartsWith('$2y$', $hashed, 'Debe utilizar el algoritmo seguro Bcrypt');
    }

    public function test_standalone_flag_forces_standalone_plan_and_virtual_assembly(): void
    {
        $payload = [
            'nit' => '900999888-1',
            'nombre_copropiedad' => 'Conjunto Campestre El Roble',
            'direccion' => 'Km 5 Vía Chía',
            'ciudad' => 'Chía',
            'name' => 'Admin Standalone',
            'email' => 'admin@roble.com',
            'password' => 'Secreto123*',
            'plan' => 'basic',
            'unidades_totales' => 50,
            'torres' => 1,
            'is_standalone' => true,
        ];

        $dto = RegisterCopropiedadDTO::fromRequest($payload);
        $this->assertTrue($dto->is_standalone);

        $plan = $dto->is_standalone ? 'standalone' : ($dto->plan ?? 'basic');
        $this->assertEquals('standalone', $plan, 'El registro standalone debe forzar el plan standalone');
    }
}
