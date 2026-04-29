<?php

namespace App\DTOs;

readonly class TransactionDataDTO
{
    public function __construct(
        public string $unidadId,
        public string $conceptoId,
        public float $monto,
        public string $tipo, // 'abono' o 'cargo'
        public string $fecha,
        public ?string $referencia = null,
        public ?string $soportePath = null,
        public ?string $userId = null
    ) {}

    public static function fromRequest(array $data, ?string $soportePath = null): self
    {
        return new self(
            unidadId: (string) $data['unidad_id'],
            conceptoId: (string) $data['concepto_id'],
            monto: (float) $data['monto'],
            tipo: $data['tipo'] ?? 'abono',
            fecha: $data['fecha'],
            referencia: $data['referencia'] ?? null,
            soportePath: $soportePath,
            userId: (string) auth()->id()
        );
    }
}
