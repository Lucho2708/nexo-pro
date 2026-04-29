<?php

namespace App\DTOs;

class RegisterCopropiedadDTO
{
    public function __construct(
        public readonly string $nit,
        public readonly string $nombre_copropiedad,
        public readonly string $direccion,
        public readonly string $ciudad,
        public readonly string $plan,
        public readonly int $unidades_totales,
        public readonly int $torres,
        public readonly string $name,
        public readonly string $email,
        public readonly string $password
    ) {}

    public static function fromRequest(array $validatedData): self
    {
        return new self(
            nit: $validatedData['nit'],
            nombre_copropiedad: $validatedData['nombre_copropiedad'],
            direccion: $validatedData['direccion'],
            ciudad: $validatedData['ciudad'],
            plan: $validatedData['plan'],
            unidades_totales: (int) $validatedData['unidades_totales'],
            torres: (int) $validatedData['torres'],
            name: $validatedData['name'],
            email: $validatedData['email'],
            password: $validatedData['password']
        );
    }
}
