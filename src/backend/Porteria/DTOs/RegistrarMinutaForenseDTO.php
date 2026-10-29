<?php

namespace NexoPro\Porteria\DTOs;

readonly class RegistrarMinutaForenseDTO
{
    public function __construct(
        public string $copropiedadId,
        public string $guardaId,
        public string $tipoEvento,
        public string $prioridad,
        public string $descripcion,
        public ?string $evidenciaFotoPath = null
    ) {
    }
}
