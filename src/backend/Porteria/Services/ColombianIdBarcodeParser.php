<?php

namespace NexoPro\Porteria\Services;

/**
 * [LÓGICA DE NEGOCIO / UX - HU-SEC-02]
 * Analizador y deserializador de códigos de barras bidimensionales (PDF417)
 * de la Cédula de Ciudadanía Colombiana (tanto formato tradicional como digital).
 *
 * [ARQUITECTURA SOLID - SRP & OCP]
 * - SRP: Exclusivamente responsable de extraer, limpiar y normalizar los datos de identidad ciudadana.
 * - OCP: Tolera múltiples formatos de entrada (stream binario con ancho fijo, cadenas delimitadas por pipes o tabs).
 */
class ColombianIdBarcodeParser
{
    /**
     * Parsea un payload crudo de escáner y extrae los campos de identidad colombiana.
     *
     * @param string $rawPayload
     * @return array|null Retorna array estructurado o null si el formato no es válido.
     */
    public function parse(string $rawPayload): ?array
    {
        $payload = trim($rawPayload);
        if ($payload === '') {
            return null;
        }

        // Caso A: Formato delimitado por barras verticales (|) o tabulaciones (\t)
        if (str_contains($payload, '|') || str_contains($payload, "\t")) {
            return $this->parseDelimited($payload);
        }

        // Caso B: Formato de ancho fijo estándar PDF417 Registraduría Nacional
        return $this->parseFixedWidthPdf417($payload);
    }

    private function parseDelimited(string $payload): ?array
    {
        $delimiter = str_contains($payload, '|') ? '|' : "\t";
        $parts = array_map('trim', explode($delimiter, $payload));

        if (count($parts) < 4) {
            return null;
        }

        $documento = ltrim($parts[0], '0');
        if (!preg_match('/^[0-9]{6,12}$/', $documento)) {
            return null;
        }

        $primerApellido = $this->sanitizeName($parts[1] ?? '');
        $segundoApellido = !empty($parts[2]) && $parts[2] !== '-' ? $this->sanitizeName($parts[2]) : null;
        $nombres = $this->sanitizeName($parts[3] ?? '');

        if ($primerApellido === '' || $nombres === '') {
            return null;
        }

        $genero = isset($parts[4]) && in_array(strtoupper($parts[4]), ['M', 'F']) ? strtoupper($parts[4]) : null;
        $fechaNacimiento = isset($parts[5]) ? $this->formatDate($parts[5]) : null;

        $nombreCompleto = trim($nombres . ' ' . $primerApellido . ($segundoApellido ? ' ' . $segundoApellido : ''));

        return [
            'documento' => $documento,
            'primer_apellido' => $primerApellido,
            'segundo_apellido' => $segundoApellido,
            'nombres' => $nombres,
            'nombre_completo' => $nombreCompleto,
            'genero' => $genero,
            'fecha_nacimiento' => $fechaNacimiento,
        ];
    }

    private function parseFixedWidthPdf417(string $payload): ?array
    {
        // El código de cédula colombiana PDF417 contiene los primeros dígitos con padding de ceros
        if (!preg_match('/^0*([0-9]{6,11})/', $payload, $docMatch)) {
            return null;
        }

        $documento = $docMatch[1];
        $resto = substr($payload, strlen($docMatch[0]));

        // Buscar género (M/F) y fecha de nacimiento (YYYYMMDD)
        if (!preg_match('/([MF])([0-9]{8})[0-9A-Z\+\-\s]*$/', $resto, $tailMatch)) {
            return null;
        }

        $genero = $tailMatch[1];
        $fechaNacimiento = $this->formatDate($tailMatch[2]);
        $posTail = strrpos($resto, $tailMatch[0]);
        $nombresBloque = substr($resto, 0, $posTail);

        // Estándar Registraduría Nacional: Primer Apellido (14 o 16 car), Segundo Apellido (14 o 16 car), Nombres (resto)
        $lenPrimer = 14;
        $lenSegundo = 14;

        if (strlen($nombresBloque) >= ($lenPrimer + $lenSegundo)) {
            $primerApellido = trim(substr($nombresBloque, 0, $lenPrimer));
            $segundoApellidoRaw = trim(substr($nombresBloque, $lenPrimer, $lenSegundo));
            $segundoApellido = $segundoApellidoRaw !== '' ? $segundoApellidoRaw : null;
            $nombres = trim(substr($nombresBloque, $lenPrimer + $lenSegundo));

            if ($primerApellido !== '' && $nombres !== '') {
                $nombreCompleto = trim($nombres . ' ' . $primerApellido . ($segundoApellido ? ' ' . $segundoApellido : ''));

                return [
                    'documento' => $documento,
                    'primer_apellido' => $primerApellido,
                    'segundo_apellido' => $segundoApellido,
                    'nombres' => $nombres,
                    'nombre_completo' => $nombreCompleto,
                    'genero' => $genero,
                    'fecha_nacimiento' => $fechaNacimiento,
                ];
            }
        }

        // Fallback genérico por separación de espacios múltiples
        $partes = preg_split('/\s{2,}/', trim($nombresBloque));
        if ($partes && count($partes) >= 2) {
            $primerApellido = trim($partes[0]);
            $segundoApellido = count($partes) >= 3 ? trim($partes[1]) : null;
            $nombres = count($partes) >= 3 ? trim($partes[2]) : trim($partes[1]);

            $nombreCompleto = trim($nombres . ' ' . $primerApellido . ($segundoApellido ? ' ' . $segundoApellido : ''));

            return [
                'documento' => $documento,
                'primer_apellido' => $primerApellido,
                'segundo_apellido' => $segundoApellido,
                'nombres' => $nombres,
                'nombre_completo' => $nombreCompleto,
                'genero' => $genero,
                'fecha_nacimiento' => $fechaNacimiento,
            ];
        }

        return null;
    }

    private function sanitizeName(string $text): string
    {
        return trim(preg_replace('/\s+/', ' ', mb_strtoupper($text, 'UTF-8')));
    }

    private function formatDate(string $digits): ?string
    {
        if (strlen($digits) === 8 && ctype_digit($digits)) {
            $year = substr($digits, 0, 4);
            $month = substr($digits, 4, 2);
            $day = substr($digits, 6, 2);

            if (checkdate((int) $month, (int) $day, (int) $year)) {
                return sprintf('%04d-%02d-%02d', (int) $year, (int) $month, (int) $day);
            }
        }
        return null;
    }
}
