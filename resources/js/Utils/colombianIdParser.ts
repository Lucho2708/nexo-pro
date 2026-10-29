/**
 * [UX / FRONTEND PREMIUM - HU-SEC-02]
 * Analizador cliente de códigos de barras PDF417 de la Cédula de Ciudadanía Colombiana.
 * Permite autocompletar en 0.05s el formulario de visitas al leer con pistola lectora USB o cámara.
 */

export interface ParsedColombianId {
    documento: string;
    primerApellido: string;
    segundoApellido: string | null;
    nombres: string;
    nombreCompleto: string;
    genero: 'M' | 'F' | null;
    fechaNacimiento: string | null;
}

export function parseColombianIdBarcode(rawPayload: string): ParsedColombianId | null {
    if (!rawPayload || typeof rawPayload !== 'string') {
        return null;
    }

    const payload = rawPayload.trim();
    if (payload.length === 0) {
        return null;
    }

    // Caso A: Formato delimitado por barras verticales (|) o tabulaciones (\t)
    if (payload.includes('|') || payload.includes('\t')) {
        const delimiter = payload.includes('|') ? '|' : '\t';
        const parts = payload.split(delimiter).map(p => p.trim());

        if (parts.length >= 4) {
            const documento = parts[0].replace(/^0+/, '');
            if (!/^[0-9]{6,12}$/.test(documento)) {
                return null;
            }

            const primerApellido = parts[1]?.toUpperCase() || '';
            const segundoApellido = parts[2] && parts[2] !== '-' ? parts[2].toUpperCase() : null;
            const nombres = parts[3]?.toUpperCase() || '';

            if (!primerApellido || !nombres) {
                return null;
            }

            const genero = parts[4] && ['M', 'F'].includes(parts[4].toUpperCase()) 
                ? (parts[4].toUpperCase() as 'M' | 'F') 
                : null;
            const fechaNacimiento = parts[5] && /^[0-9]{8}$/.test(parts[5])
                ? `${parts[5].slice(0, 4)}-${parts[5].slice(4, 6)}-${parts[5].slice(6, 8)}`
                : null;

            const nombreCompleto = `${nombres} ${primerApellido}${segundoApellido ? ' ' + segundoApellido : ''}`.trim();

            return {
                documento,
                primerApellido,
                segundoApellido,
                nombres,
                nombreCompleto,
                genero,
                fechaNacimiento,
            };
        }
    }

    // Caso B: Formato de ancho fijo estándar PDF417 Registraduría Nacional
    const docMatch = payload.match(/^0*([0-9]{6,11})/);
    if (!docMatch) {
        return null;
    }

    const documento = docMatch[1];
    const resto = payload.slice(docMatch[0].length);

    const tailMatch = resto.match(/([MF])([0-9]{8})[0-9A-Z\+\-\s]*$/);
    if (!tailMatch) {
        return null;
    }

    const genero = tailMatch[1] as 'M' | 'F';
    const fechaRaw = tailMatch[2];
    const fechaNacimiento = `${fechaRaw.slice(0, 4)}-${fechaRaw.slice(4, 6)}-${fechaRaw.slice(6, 8)}`;

    const posTail = resto.lastIndexOf(tailMatch[0]);
    const nombresBloque = resto.slice(0, posTail);

    const lenPrimer = 14;
    const lenSegundo = 14;

    if (nombresBloque.length >= lenPrimer + lenSegundo) {
        const primerApellido = nombresBloque.slice(0, lenPrimer).trim().toUpperCase();
        const segundoApellidoRaw = nombresBloque.slice(lenPrimer, lenPrimer + lenSegundo).trim().toUpperCase();
        const segundoApellido = segundoApellidoRaw.length > 0 ? segundoApellidoRaw : null;
        const nombres = nombresBloque.slice(lenPrimer + lenSegundo).trim().toUpperCase();

        if (primerApellido && nombres) {
            const nombreCompleto = `${nombres} ${primerApellido}${segundoApellido ? ' ' + segundoApellido : ''}`.trim();

            return {
                documento,
                primerApellido,
                segundoApellido,
                nombres,
                nombreCompleto,
                genero,
                fechaNacimiento,
            };
        }
    }

    return null;
}
