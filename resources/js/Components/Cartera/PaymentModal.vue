<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import Modal from '@/Components/UI/Modal.vue';
import Button from '@/Components/UI/Button.vue';
import Input from '@/Components/UI/Input.vue';
import Select from '@/Components/UI/Select.vue';
import DatePicker from '@/Components/UI/DatePicker.vue';

const props = defineProps<{
    unidad: any;
    conceptos: Array<any>;
}>();

const emit = defineEmits(['close']);

const fileInput = ref<HTMLInputElement | null>(null);

const form = useForm({
    unidad_id: props.unidad?.id || '',
    concepto_id: '',
    monto: '',
    fecha: new Date().toISOString().split('T')[0],
    referencia: '',
    soporte: null as File | null,
});

const submit = () => {
    form.post(route('cartera.payment'), {
        onSuccess: () => emit('close'),
        forceFormData: true,
    });
};

const handleFileChange = (e: Event) => {
    const target = e.target as HTMLInputElement;
    if (target.files && target.files.length > 0) {
        form.soporte = target.files[0];
    }
};
</script>

<template>
    <Modal :show="true" max-width="xl" @close="emit('close')">
        <div class="mb-10">
            <h2 class="text-3xl font-black text-primary tracking-tighter uppercase leading-none">Registrar Recaudo</h2>
            <p class="text-on-surface-variant/60 text-sm font-medium mt-2">
                {{ unidad ? `Abono para: ${unidad.torre} ${unidad.nombre}` : 'Ingrese los detalles del pago manual para la unidad.' }}
            </p>
        </div>

        <form @submit.prevent="submit" class="space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Concepto with Select -->
                <div class="col-span-1 md:col-span-2">
                    <Select 
                        v-model="form.concepto_id"
                        label="Concepto de Cobro"
                        placeholder="Seleccione el concepto..."
                        icon="receipt_long"
                        :options="conceptos.map(c => ({ value: c.id, label: c.nombre }))"
                        :error="form.errors.concepto_id"
                    />
                </div>

                <!-- Monto -->
                <Input 
                    v-model="form.monto"
                    label="Valor Recibido"
                    type="number"
                    icon="payments"
                    placeholder="0.00"
                    required
                    :error="form.errors.monto"
                />

                <!-- Fecha with DatePicker -->
                <DatePicker 
                    v-model="form.fecha"
                    label="Fecha de Transacción"
                    icon="calendar_month"
                    :error="form.errors.fecha"
                />

                <!-- Referencia -->
                <Input 
                    v-model="form.referencia"
                    label="Referencia / Comprobante"
                    placeholder="No. de consignación"
                    icon="confirmation_number"
                    class="md:col-span-2"
                    :error="form.errors.referencia"
                />
            </div>

            <!-- Soporte Upload -->
            <div class="space-y-2">
                <label class="text-[10px] font-black text-on-surface-variant/60 uppercase tracking-widest pl-1">Adjuntar Soporte Digital</label>
                <div 
                    class="border-2 border-dashed border-outline-variant/20 rounded-[2rem] p-10 text-center bg-surface-container-low/30 hover:bg-surface-container-low hover:border-primary/30 transition-all cursor-pointer group relative"
                    @click="fileInput?.click()"
                >
                    <input type="file" ref="fileInput" class="hidden" @change="handleFileChange" accept=".pdf,.jpg,.jpeg,.png" />
                    
                    <div class="flex flex-col items-center gap-3">
                        <div class="w-16 h-16 rounded-2xl bg-primary/5 text-primary flex items-center justify-center group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-3xl" style="font-variation-settings: 'FILL' 1;">cloud_upload</span>
                        </div>
                        <div>
                            <p class="text-sm font-black text-primary uppercase tracking-tight">
                                {{ form.soporte ? form.soporte.name : 'Subir comprobante' }}
                            </p>
                            <p class="text-[10px] text-on-surface-variant/40 mt-1 font-bold uppercase tracking-widest">PDF, JPG o PNG • Máximo 5MB</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 pt-4">
                <Button 
                    variant="ghost" 
                    class="flex-1 order-2 sm:order-1" 
                    @click="emit('close')"
                >
                    Cancelar
                </Button>
                <Button 
                    type="submit" 
                    variant="secondary" 
                    class="flex-1 order-1 sm:order-2" 
                    icon="check_circle"
                    :loading="form.processing"
                >
                    Registrar Pago
                </Button>
            </div>

            <!-- Secure Footer -->
            <div class="flex items-center justify-center gap-2 py-4 border-t border-outline-variant/5">
                <span class="material-symbols-outlined text-on-surface-variant/30 text-sm">verified_user</span>
                <span class="text-[9px] font-black text-on-surface-variant/30 uppercase tracking-[0.2em]">Registro financiero auditado por NEXO-PRO</span>
            </div>
        </form>
    </Modal>
</template>

<style scoped>
.material-symbols-outlined {
    font-variation-settings: 'opsz' 24;
}
</style>
