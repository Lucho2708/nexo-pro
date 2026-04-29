<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted, nextTick, watch } from 'vue';

interface Props {
    modelValue: string | null;
    label?: string;
    icon?: string;
    error?: string;
    disabled?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    icon: 'calendar_today',
    disabled: false,
});

const emit = defineEmits(['update:modelValue']);

// Estados
const isOpen = ref(false);
const triggerRef = ref<HTMLElement | null>(null);
const calendarRef = ref<HTMLElement | null>(null);
const viewDate = ref(new Date()); 
const dropdownStyle = ref({ top: '0px', left: '0px', width: 'auto' });

// Estado local para persistencia visual inmediata
const localValue = ref(props.modelValue);

watch(() => props.modelValue, (newVal) => {
    localValue.value = newVal;
});

// Calendario
const months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
const days = ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'];

const calendarDays = computed(() => {
    const year = viewDate.value.getFullYear();
    const month = viewDate.value.getMonth();
    const startDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const daysInPrevMonth = new Date(year, month, 0).getDate();
    
    const prev = [];
    for (let i = startDay - 1; i >= 0; i--) {
        prev.push({ day: daysInPrevMonth - i, current: false, date: new Date(year, month - 1, daysInPrevMonth - i) });
    }
    const current = [];
    for (let i = 1; i <= daysInMonth; i++) {
        current.push({ day: i, current: true, date: new Date(year, month, i) });
    }
    const next = [];
    const remaining = 42 - (prev.length + current.length);
    for (let i = 1; i <= remaining; i++) {
        next.push({ day: i, current: false, date: new Date(year, month + 1, i) });
    }
    return [...prev, ...current, ...next];
});

const updatePosition = () => {
    if (!triggerRef.value) return;
    const rect = triggerRef.value.getBoundingClientRect();
    
    let left = rect.left;
    const width = 280;
    if (left + width > window.innerWidth) left = window.innerWidth - width - 20;

    dropdownStyle.value = {
        top: `${rect.bottom + 8}px`,
        left: `${left}px`,
        width: `${width}px`
    };
};

const toggle = async () => {
    if (props.disabled) return;
    if (!isOpen.value) {
        if (localValue.value) {
            const d = new Date(localValue.value + 'T00:00:00');
            if (!isNaN(d.getTime())) viewDate.value = d;
        }
        isOpen.value = true;
        await nextTick();
        updatePosition();
        window.addEventListener('scroll', updatePosition, true);
        window.addEventListener('resize', updatePosition);
    } else {
        closeCalendar();
    }
};

const closeCalendar = () => {
    isOpen.value = false;
    window.removeEventListener('scroll', updatePosition, true);
    window.removeEventListener('resize', updatePosition);
};

const selectDate = (date: Date) => {
    const y = date.getFullYear();
    const m = String(date.getMonth() + 1).padStart(2, '0');
    const d = String(date.getDate()).padStart(2, '0');
    const formatted = `${y}-${m}-${d}`;
    
    localValue.value = formatted; // Update visual state immediately
    emit('update:modelValue', formatted);
    closeCalendar();
};

const isSelected = (date: Date) => {
    if (!localValue.value) return false;
    const d = new Date(localValue.value + 'T00:00:00');
    return date.toDateString() === d.toDateString();
};

const isToday = (date: Date) => date.toDateString() === new Date().toDateString();

const formatDateDisplay = (dateString: string | null) => {
    if (!dateString) return 'Seleccionar fecha';
    const date = new Date(dateString + 'T00:00:00');
    if (isNaN(date.getTime())) return 'Seleccionar fecha';
    return date.toLocaleDateString('es-ES', { day: '2-digit', month: 'long', year: 'numeric' });
};

const handleOutsideClick = (e: MouseEvent) => {
    if (isOpen.value && 
        triggerRef.value && !triggerRef.value.contains(e.target as Node) && 
        calendarRef.value && !calendarRef.value.contains(e.target as Node)) {
        closeCalendar();
    }
};

onMounted(() => document.addEventListener('mousedown', handleOutsideClick));
onUnmounted(() => {
    document.removeEventListener('mousedown', handleOutsideClick);
    closeCalendar();
});
</script>

<template>
    <div class="space-y-1.5 w-full relative">
        <label v-if="label" class="text-[10px] font-black text-on-surface-variant/60 uppercase tracking-widest pl-1">
            {{ label }}
        </label>
        
        <div class="relative" ref="triggerRef">
            <button
                type="button"
                @click.stop="toggle"
                :disabled="disabled"
                class="w-full bg-surface-container-low border-2 rounded-2xl p-4 text-sm font-medium transition-all outline-none flex items-center justify-between group"
                :class="[
                    error ? 'border-error/50' : 'border-outline-variant/10 focus:ring-4 focus:ring-primary/10',
                    disabled ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer hover:border-primary/30'
                ]"
            >
                <div class="flex items-center gap-3 pointer-events-none">
                    <span class="material-symbols-outlined text-on-surface-variant/40 group-hover:text-primary transition-colors">
                        {{ icon }}
                    </span>
                    <span :class="localValue ? 'text-on-surface' : 'text-on-surface-variant/40'">
                        {{ formatDateDisplay(localValue) }}
                    </span>
                </div>
                <span class="material-symbols-outlined text-on-surface-variant/40 transition-transform duration-300 pointer-events-none" :class="{'rotate-180 text-primary': isOpen}">
                    calendar_month
                </span>
            </button>
        </div>

        <Teleport to="body">
            <Transition
                enter-active-class="transition duration-200 ease-out"
                enter-from-class="opacity-0 scale-95 translate-y-2"
                enter-to-class="opacity-100 scale-100 translate-y-0"
                leave-active-class="transition duration-150 ease-in"
                leave-from-class="opacity-100 scale-100 translate-y-0"
                leave-to-class="opacity-0 scale-95 translate-y-2"
            >
                <div 
                    v-if="isOpen" 
                    ref="calendarRef"
                    :style="dropdownStyle"
                    @mousedown.stop
                    class="fixed z-[9999] bg-surface border border-outline-variant/20 rounded-[2rem] shadow-[0_20px_50px_rgba(0,0,0,0.3)] overflow-hidden p-5 select-none no-scrollbar"
                >
                    <div class="flex items-center justify-between mb-6">
                        <button type="button" @click="viewDate = new Date(viewDate.getFullYear(), viewDate.getMonth() - 1, 1)" class="w-10 h-10 rounded-xl hover:bg-surface-container-low flex items-center justify-center text-on-surface-variant transition-colors">
                            <span class="material-symbols-outlined">chevron_left</span>
                        </button>
                        <div class="text-center">
                            <p class="text-xs font-black text-primary uppercase tracking-widest">{{ months[viewDate.getMonth()] }}</p>
                            <p class="text-[10px] font-bold text-on-surface-variant/40">{{ viewDate.getFullYear() }}</p>
                        </div>
                        <button type="button" @click="viewDate = new Date(viewDate.getFullYear(), viewDate.getMonth() + 1, 1)" class="w-10 h-10 rounded-xl hover:bg-surface-container-low flex items-center justify-center text-on-surface-variant transition-colors">
                            <span class="material-symbols-outlined">chevron_right</span>
                        </button>
                    </div>

                    <div class="grid grid-cols-7 gap-1 mb-2">
                        <div v-for="day in days" :key="day" class="text-[9px] font-black text-center text-on-surface-variant/30 uppercase py-2">{{ day }}</div>
                    </div>
                    
                    <div class="grid grid-cols-7 gap-1">
                        <button
                            v-for="(dateObj, i) in calendarDays"
                            :key="i"
                            type="button"
                            @click.stop="selectDate(dateObj.date)"
                            class="aspect-square rounded-xl flex items-center justify-center text-xs font-bold transition-all relative"
                            :class="[
                                !dateObj.current ? 'text-on-surface-variant/20 opacity-50' : 'text-on-surface',
                                isSelected(dateObj.date) ? 'bg-primary text-on-primary shadow-lg scale-110 z-10' : 'hover:bg-primary/10 hover:text-primary',
                                isToday(dateObj.date) && !isSelected(dateObj.date) ? 'text-secondary' : ''
                            ]"
                        >
                            {{ dateObj.day }}
                            <div v-if="isToday(dateObj.date)" class="absolute bottom-1.5 w-1 h-1 rounded-full bg-secondary"></div>
                        </button>
                    </div>

                    <div class="mt-6 pt-4 border-t border-outline-variant/10 flex justify-between">
                        <button type="button" @click="selectDate(new Date())" class="text-[10px] font-black text-secondary uppercase tracking-widest hover:underline">Hoy</button>
                        <button type="button" @click="closeCalendar" class="text-[10px] font-black text-on-surface-variant/40 uppercase tracking-widest hover:text-on-surface transition-colors">Cerrar</button>
                    </div>
                </div>
            </Transition>
        </Teleport>
        
        <p v-if="error" class="text-[9px] font-bold text-error uppercase tracking-widest pl-1">
            {{ error }}
        </p>
    </div>
</template>
