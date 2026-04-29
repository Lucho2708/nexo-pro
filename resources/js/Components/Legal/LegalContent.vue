<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
    type: 'privacy' | 'terms' | 'cookies';
}>();
</script>

<template>
    <div class="space-y-8 text-on-surface">
        
        <!-- PRIVACY POLICY (LEY 1581 COLOMBIA) -->
        <template v-if="type === 'privacy'">
            <div class="p-6 bg-surface-container-low rounded-3xl border border-outline-variant/30 flex items-start gap-4">
                <span class="material-symbols-outlined text-4xl text-primary shrink-0">data_alert</span>
                <div>
                    <h2 class="text-xl font-black uppercase tracking-tighter mb-1 text-primary">Tratamiento de Datos</h2>
                    <p class="text-xs font-bold text-on-surface-variant leading-relaxed">De conformidad con la Ley Estatutaria 1581 de 2012 y el Decreto 1377 de 2013 de la República de Colombia para el respeto al Habeas Data.</p>
                </div>
            </div>
            
            <div class="premium-card p-6 md:p-8 space-y-6">
                <!-- Section 1 -->
                <div class="group">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex justify-center items-center font-black text-xs">1</div>
                        <h3 class="text-sm font-black uppercase tracking-widest text-on-surface group-hover:text-primary transition-colors">Identificación del Responsable</h3>
                    </div>
                    <div class="pl-11 text-xs text-on-surface-variant font-medium space-y-1">
                        <p><strong class="text-on-surface">Razón Social:</strong> NEXO-PRO SOLUTIONS S.A.S.</p>
                        <p><strong class="text-on-surface">NIT:</strong> 900.000.000-0</p>
                        <p><strong class="text-on-surface">Domicilio:</strong> Colombia</p>
                        <p><strong class="text-on-surface">Correo Electrónico:</strong> habeasdata@nexo-pro.com</p>
                    </div>
                </div>

                <div class="h-px bg-outline-variant/10 w-full"></div>

                <!-- Section 2 -->
                <div class="group">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex justify-center items-center font-black text-xs">2</div>
                        <h3 class="text-sm font-black uppercase tracking-widest text-on-surface group-hover:text-primary transition-colors">Objetivo de la Política</h3>
                    </div>
                    <div class="pl-11 text-xs text-on-surface-variant font-medium leading-relaxed">
                        La presente política tiene el propósito de garantizar el derecho constitucional al Habeas Data que tienen todas las personas que suministren sus datos personales a NEXO-PRO, en relación a su conocimiento, actualización, rectificación y supresión de la información almacenada en nuestras bases de datos.
                    </div>
                </div>

                <div class="h-px bg-outline-variant/10 w-full"></div>

                <!-- Section 3 -->
                <div class="group">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex justify-center items-center font-black text-xs">3</div>
                        <h3 class="text-sm font-black uppercase tracking-widest text-on-surface group-hover:text-primary transition-colors">Finalidad del Tratamiento</h3>
                    </div>
                    <div class="pl-11 text-xs text-on-surface-variant font-medium leading-relaxed space-y-3">
                        <p>Los datos personales recolectados a través del registro en la plataforma (Nombres, correos, documentos de identidad y de representación legal de copropiedades) tienen como propósito fundamental:</p>
                        <ul class="space-y-2 list-none">
                            <li class="flex gap-2"><span class="material-symbols-outlined text-[16px] text-primary">check_circle</span> Prestar los servicios de software SaaS para la administración de copropiedades.</li>
                            <li class="flex gap-2"><span class="material-symbols-outlined text-[16px] text-primary">check_circle</span> Mantener canales de comunicación corporativa y enviar notificaciones relativas al servicio, facturación, soporte y actualizaciones.</li>
                            <li class="flex gap-2"><span class="material-symbols-outlined text-[16px] text-primary">check_circle</span> Integración con pasarelas transaccionales legales para facilitar los recaudos operativos (pagos).</li>
                        </ul>
                        <div class="bg-surface-container-low p-4 rounded-xl border-l-4 border-primary">
                            <p class="font-bold text-on-surface">En ningún escenario venderemos, comercializaremos ni alquilaremos bases de datos a terceros con fines publicitarios.</p>
                        </div>
                    </div>
                </div>

                <div class="h-px bg-outline-variant/10 w-full"></div>

                <!-- Section 4 -->
                <div class="group">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex justify-center items-center font-black text-xs">4</div>
                        <h3 class="text-sm font-black uppercase tracking-widest text-on-surface group-hover:text-primary transition-colors">Derechos de los Titulares (Ley 1581)</h3>
                    </div>
                    <div class="pl-11 text-xs text-on-surface-variant font-medium leading-relaxed space-y-3">
                        <p>Como Titular de los datos personales tienes derecho a:</p>
                        <ul class="space-y-2 list-none">
                            <li class="flex gap-2"><span class="text-primary font-black">1.</span> <strong class="text-on-surface">Conocer, actualizar y rectificar:</strong> Este derecho se podrá ejercer frente a datos parciales, inexactos, fraccionados o engañosos.</li>
                            <li class="flex gap-2"><span class="text-primary font-black">2.</span> <strong class="text-on-surface">Solicitar constancia:</strong> De la autorización otorgada para el Tratamiento inicial.</li>
                            <li class="flex gap-2"><span class="text-primary font-black">3.</span> <strong class="text-on-surface">Ser informado:</strong> Previa solicitud, del uso que se le ha dado a tus datos personales en el entorno.</li>
                            <li class="flex gap-2"><span class="text-primary font-black">4.</span> <strong class="text-on-surface">Revocar y Suprimir:</strong> En un término de máximo 15 días hábiles, cuando en el Tratamiento no se respeten derechos y garantías institucionales.</li>
                        </ul>
                    </div>
                </div>

                <div class="h-px bg-outline-variant/10 w-full"></div>

                <!-- Section 5 -->
                <div class="group">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex justify-center items-center font-black text-xs">5</div>
                        <h3 class="text-sm font-black uppercase tracking-widest text-on-surface group-hover:text-primary transition-colors">Canal Institucional y Vigencia</h3>
                    </div>
                    <div class="pl-11 text-xs text-on-surface-variant font-medium leading-relaxed">
                        Las consultas o supresiones de la información personal podrán gestionarse a través del correo <strong class="text-on-surface">habeasdata@nexo-pro.com</strong>. Trataremos sus consultas bajo las políticas estipuladas por la Superintendencia de Industria y Comercio (SIC). La presente política mantiene vigencia indefinida mientras se mantenga infraestructura SaaS activa en Nexo-Pro.
                    </div>
                </div>

            </div>
        </template>


        <!-- TERMS AND CONDITIONS -->
        <template v-else-if="type === 'terms'">
            <div class="p-6 bg-surface-container-low rounded-3xl border border-outline-variant/30 flex items-start gap-4">
                <span class="material-symbols-outlined text-4xl text-secondary shrink-0">gavel</span>
                <div>
                    <h2 class="text-xl font-black uppercase tracking-tighter mb-1 text-secondary">Términos de Servicio</h2>
                    <p class="text-xs font-bold text-on-surface-variant leading-relaxed">Contrato de licenciamiento y uso de la arquitectura SaaS B2B de NEXO-PRO.</p>
                </div>
            </div>
            
            <div class="premium-card p-6 md:p-8 space-y-6">
                <!-- S1 -->
                <div class="group">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="material-symbols-outlined text-primary">contract</span>
                        <h3 class="text-sm font-black uppercase tracking-widest text-on-surface">Aceptación y Licenciamiento B2B</h3>
                    </div>
                    <div class="pl-9 text-xs text-on-surface-variant font-medium leading-relaxed space-y-2">
                        <p>Al utilizar nuestra plataforma (el "SaaS"), usted atribuye la condición de Usuario Administrador y se obliga sin equidad implícita a todas las disposiciones aquí consagradas.</p>
                        <p>NEXO-PRO le concede un acceso revocable, intransferible y no exclusivo a nuestro software residencial bajo el pago oportuno de un plan de suscripción temporal.</p>
                    </div>
                </div>

                <div class="h-px bg-outline-variant/10 w-full"></div>

                <!-- S2 -->
                <div class="group">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="material-symbols-outlined text-warning">account_balance</span>
                        <h3 class="text-sm font-black uppercase tracking-widest text-on-surface">Límites Profesionales Financieros</h3>
                    </div>
                    <div class="pl-9 text-xs text-on-surface-variant font-medium leading-relaxed">
                        Bajo ningún escenario NEXO-PRO funge como banco, red de recaudo ni tesorería delegada, ni recibe fondos masivos de la copropiedad. Toda gestión bancaria está sub-integrada mediante <strong>Pasarelas de Pago Seguras Agregadoras</strong> externas (ej: ePayco, Wompi). Cualquier diferencia de liquidación de dinero cobrado deberá ser disputada con la entidad financiera adquirente externa, y no con Nexo-Pro ya que nosotros prestamos únicamente el ecosistema visual operativo web.
                    </div>
                </div>

                <div class="h-px bg-outline-variant/10 w-full"></div>

                <!-- S3 -->
                <div class="group">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="material-symbols-outlined text-error">gpp_bad</span>
                        <h3 class="text-sm font-black uppercase tracking-widest text-on-surface">Obligaciones y Cancelación Inmediata</h3>
                    </div>
                    <div class="pl-9 text-xs text-on-surface-variant font-medium leading-relaxed">
                        El Administrador es responsable íntegro de la veracidad de la cartera que cobre. Las difamaciones vecinales en los módulos sociales, fraudes financieros visuales o uso para piratería tecnológica dentro del servidor significará la inmediata <strong>Desactivación y Bloqueo Definitivo de sus credenciales</strong>, entregando el material a las autoridades Nacionales pertinentes.
                    </div>
                </div>
            </div>
        </template>


        <!-- COOKIE POLICY -->
        <template v-else-if="type === 'cookies'">
            <div class="p-6 bg-surface-container-low rounded-3xl border border-outline-variant/30 flex items-start gap-4">
                <span class="material-symbols-outlined text-4xl text-warning shrink-0">cookie</span>
                <div>
                    <h2 class="text-xl font-black uppercase tracking-tighter mb-1 text-warning">Transparencia: Cookies</h2>
                    <p class="text-xs font-bold text-on-surface-variant leading-relaxed">Protegemos tu entorno digital, por eso bloqueamos el espionaje silencioso.</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 gap-4 mt-6">
                <!-- T1 -->
                <div class="premium-card p-6 flex flex-col md:flex-row gap-6 relative overflow-hidden group">
                    <div class="absolute -right-4 -bottom-4 text-primary/5 group-hover:scale-110 transition-transform duration-500">
                        <span class="material-symbols-outlined text-[100px]">verified_user</span>
                    </div>
                    <div class="shrink-0 flex items-center justify-center w-12 h-12 bg-primary/10 rounded-2xl text-primary mt-1 z-10">
                        <span class="material-symbols-outlined">key</span>
                    </div>
                    <div class="z-10">
                        <h3 class="text-sm font-black uppercase tracking-widest text-on-surface mb-2">Cookies Estrictamente Técnicas</h3>
                        <p class="text-xs text-on-surface-variant font-medium leading-relaxed">Obligatorias para no ser vulnerados. Contienen memorizaciones cifradas (Tokens CSRF de Laravel) impidiendo inyecciones maliciosas de hackers entre las pantallas de la plataforma. Si su navegador apaga estas cookies corporativas, el servidor prohibirá todas las transacciones habitacionales por su propia protección.</p>
                    </div>
                </div>

                <!-- T2 -->
                <div class="premium-card p-6 flex flex-col md:flex-row gap-6 relative overflow-hidden group">
                    <div class="absolute -right-4 -bottom-4 text-secondary/5 group-hover:scale-110 transition-transform duration-500">
                        <span class="material-symbols-outlined text-[100px]">timer</span>
                    </div>
                    <div class="shrink-0 flex items-center justify-center w-12 h-12 bg-secondary/10 rounded-2xl text-secondary mt-1 z-10">
                        <span class="material-symbols-outlined">clock_loader_40</span>
                    </div>
                    <div class="z-10">
                        <h3 class="text-sm font-black uppercase tracking-widest text-on-surface mb-2">Sesión Activa in-App</h3>
                        <p class="text-xs text-on-surface-variant font-medium leading-relaxed">Mantiene su ingreso temporal mientras gestiona facturas de los propietarios. Duran exactamente el tiempo que permanezca utilizando su navegador (hasta 120 minutos estándar). Jamás rastrearán su conducta si cambia de pantalla hacia Amazon o Google.</p>
                    </div>
                </div>

                <!-- T3 -->
                <div class="premium-card p-6 flex flex-col md:flex-row gap-6 relative overflow-hidden group">
                    <div class="absolute -right-4 -bottom-4 text-info/5 group-hover:scale-110 transition-transform duration-500">
                        <span class="material-symbols-outlined text-[100px]">palette</span>
                    </div>
                    <div class="shrink-0 flex items-center justify-center w-12 h-12 bg-info/10 rounded-2xl text-info mt-1 z-10">
                        <span class="material-symbols-outlined">contrast</span>
                    </div>
                    <div class="z-10">
                        <h3 class="text-sm font-black uppercase tracking-widest text-on-surface mb-2">Personalización Extendida (UI/UX)</h3>
                        <p class="text-xs text-on-surface-variant font-medium leading-relaxed">Memorizamos su elección visual. Si decide accionar el Sistema Premium "Dark Mode" para relajar su vista mientras liquida impuestos en horas nocturnas; el framework lo recodará en su siguiente encendido.</p>
                    </div>
                </div>
            </div>

        </template>

    </div>
</template>
