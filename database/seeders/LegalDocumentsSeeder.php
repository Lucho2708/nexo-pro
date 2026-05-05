<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\IAM\Models\LegalDocument;

class LegalDocumentsSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar previos para asegurar que esta versión sea la única activa
        LegalDocument::whereIn('type', ['terms', 'privacy'])->update(['is_active' => false]);

        // 1. TÉRMINOS Y CONDICIONES (PARTE I)
        LegalDocument::create([
            'type' => 'terms',
            'title' => 'Términos y Condiciones de Uso - NEXO-PRO v1.0.1',
            'version' => '1.0.1',
            'is_active' => true,
            'body' => "BIENVENIDO A NEXO-PRO. La transparencia y la protección de su información son nuestra prioridad.\n\n" .
                      "PARTE I: TÉRMINOS Y CONDICIONES DE USO\n\n" .
                      "Al acceder y utilizar esta plataforma (en adelante, la 'Plataforma' o 'NEXO-PRO'), usted acepta los siguientes términos y condiciones:\n\n" .
                      "1. Naturaleza del Servicio: NEXO-PRO provee una solución de software como servicio (SaaS) para la gestión integral de propiedad horizontal. El usuario reconoce y acepta que NEXO-PRO es un facilitador tecnológico y NO es responsable de la administración financiera, contable, legal ni operativa de la Copropiedad.\n\n" .
                      "2. Asambleas Virtuales: La plataforma permite la participación en asambleas no presenciales conforme a la Ley 675 de 2001 y el Decreto 398 de 2020. NEXO-PRO garantiza la integridad y el rastro de auditoría de los votos registrados en el sistema. Sin embargo, la estabilidad de la conexión a internet, el hardware del usuario y la validación del quórum son responsabilidad compartida entre el usuario y el administrador/moderador de la asamblea.\n\n" .
                      "3. Gestión de Recaudo: Los pagos realizados a través de la plataforma son procesados por pasarelas de pago externas debidamente autorizadas (ej. Wompi, PayU). NEXO-PRO NO almacena información financiera sensible como números de tarjetas de crédito o credenciales bancarias. Cualquier disputa sobre la aplicación de pagos deberá ser dirigida directamente a la administración de su Copropiedad.\n\n" .
                      "4. Limitación de Responsabilidad: En la medida máxima permitida por la ley colombiana, NEXO-PRO no será responsable por daños indirectos, lucro cesante o la eventual nulidad de decisiones tomadas en asamblea derivadas de fallos técnicos de terceros, interrupciones del servicio de internet, fuerza mayor o el uso indebido de la plataforma por parte de los usuarios.\n\n" .
                      "5. Propiedad Intelectual: El diseño, código fuente, logotipos, bases de datos y arquitectura de software de NEXO-PRO son propiedad exclusiva de NEXO-PRO SAS. Queda estrictamente prohibida su reproducción, copia, distribución o ingeniería inversa."
        ]);

        // 2. POLÍTICA DE PRIVACIDAD Y HABEAS DATA (PARTE II)
        LegalDocument::create([
            'type' => 'privacy',
            'title' => 'Política de Tratamiento de Datos Personales (Habeas Data) - NEXO-PRO',
            'version' => '1.0.1',
            'is_active' => true,
            'body' => "PARTE II: POLÍTICA DE TRATAMIENTO DE DATOS PERSONALES Y HABEAS DATA\n\n" .
                      "1. Identificación de las Partes (Responsable y Encargado):\n" .
                      "* El Responsable del Tratamiento: Es la Copropiedad (Edificio, Conjunto o Centro Comercial) donde usted reside o tiene su inmueble.\n" .
                      "* El Encargado del Tratamiento: Es NEXO-PRO SAS, con NIT [COMPLETAR NIT], dirección [COMPLETAR DIRECCIÓN], y correo de contacto legal@nexo-pro.com. Nosotros procesamos los datos en nombre de la Copropiedad.\n\n" .
                      "2. Marco Normativo Aplicable: Esta política se rige bajo la Constitución Política de Colombia (Art. 15), la Ley Estatutaria 1581 de 2012, el Decreto 1074 de 2015, la Ley 1266 de 2008, la Resolución 500 de 2021 del MinTIC, y la Circular Externa No. 002 de 2024 de la SIC.\n\n" .
                      "3. Definiciones Clave: Se definen Datos Personales, Datos Sensibles, Titular y Tratamiento conforme a la normativa vigente.\n\n" .
                      "4. Tratamiento y Finalidades: Sus datos serán tratados para fines Administrativos (gestión de cartera), Operativos (notificaciones y correspondencia), Tecnológicos (registro de votaciones en asambleas) y de Mejora Continua (análisis UI/UX).\n\n" .
                      "5. Datos Sensibles y Menores: El suministro de datos sensibles es facultativo. Los datos biométricos en asambleas virtuales se usan solo para validar identidad con seguridad reforzada. El tratamiento de datos de menores requiere autorización de sus representantes legales.\n\n" .
                      "6. Uso de Inteligencia Artificial (IA): En cumplimiento de la Circular 002 de 2024 de la SIC, NEXO-PRO garantiza que el uso de herramientas de IA se rige por principios de transparencia y supervisión humana, evitando decisiones automatizadas discriminatorias.\n\n" .
                      "7. Derechos de los Titulares (ARCOPLI): Usted tiene derecho al Acceso, Rectificación, Cancelación, Oposición, Portabilidad y Limitación de sus datos.\n\n" .
                      "8. Revocatoria: Usted puede revocar su consentimiento siempre que no exista un deber legal o contractual pendiente.\n\n" .
                      "9. Seguridad: Implementamos cifrado SSL/TLS, arquitectura multi-tenant aislada, MFA y copias de seguridad inmutables (MSPI - Res. 500 de 2021).\n\n" .
                      "10. Transferencia Internacional: Sus datos se alojan en servidores seguros (AWS/Google Cloud) con niveles de protección iguales o superiores a la ley colombiana.\n\n" .
                      "11. Vigencia: Los datos se conservarán durante la relación contractual y hasta 10 años después para cumplimiento legal.\n\n" .
                      "12. RNBD: NEXO-PRO SAS cumple con la inscripción y actualización anual obligatoria de sus bases de datos ante la SIC.\n\n" .
                      "13. PQRS: Las consultas se atenderán en 10 días hábiles y los reclamos en 15 días hábiles a través de legal@nexo-pro.com.\n\n" .
                      "14. Privacidad desde el Diseño: Aplicamos minimización de datos y limitación de acceso por roles desde la arquitectura inicial.\n\n" .
                      "15. Aceptación: Al utilizar nuestros servicios, usted declara la aceptación libre, previa, expresa e informada de esta política."
        ]);
    }
}
