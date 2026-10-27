<?php

namespace Tests\Unit\Database;

use PHPUnit\Framework\TestCase;

class TargetedDemoDataSeederTest extends TestCase
{
    public function test_target_demo_users_specification_structure(): void
    {
        $expectedUsers = [
            'admin_simple@nexo.pro' => [
                'role' => 'admin',
                'expected_managed_copropiedades' => 1,
                'description' => 'Administrador que tiene 1 conjunto',
            ],
            'admin_multi@nexo.pro' => [
                'role' => 'admin',
                'expected_managed_copropiedades' => 2,
                'description' => 'Administrador que tiene varios conjuntos',
            ],
            'propietario_simple@nexo.pro' => [
                'role' => 'owner',
                'expected_properties' => 1,
                'description' => 'Usuario que tiene 1 propiedad',
            ],
            'propietario_multi@nexo.pro' => [
                'role' => 'owner',
                'expected_properties' => 2,
                'description' => 'Usuario que tiene 2 propiedades',
            ],
        ];

        $this->assertCount(4, $expectedUsers);
        $this->assertEquals(1, $expectedUsers['admin_simple@nexo.pro']['expected_managed_copropiedades']);
        $this->assertGreaterThan(1, $expectedUsers['admin_multi@nexo.pro']['expected_managed_copropiedades']);
        $this->assertEquals(1, $expectedUsers['propietario_simple@nexo.pro']['expected_properties']);
        $this->assertEquals(2, $expectedUsers['propietario_multi@nexo.pro']['expected_properties']);
    }
}
