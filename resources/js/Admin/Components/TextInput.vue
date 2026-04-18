<template>
    <div class="mb-5">
        <label v-if="label" :for="id" class="block text-sm font-medium text-gray-300 mb-2">{{ label }}</label>
        
        <div class="relative group">
            <div v-if="$slots.icon" class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-electric transition-colors">
                <slot name="icon"></slot>
            </div>
            
            <input 
                :type="type" 
                :id="id" 
                :value="modelValue"
                @input="$emit('update:modelValue', $event.target.value)"
                :class="[
                    'w-full bg-dark-900 border rounded-xl py-3 text-white focus:outline-none focus:ring-2 focus:ring-electric/50 transition-all placeholder-gray-500 shadow-inner',
                    $slots.icon ? 'pl-11 pr-4' : 'px-4',
                    error ? 'border-red-500 focus:border-red-500' : 'border-dark-600 focus:border-electric hover:border-dark-500'
                ]"
                :placeholder="placeholder"
                :required="required"
                :disabled="disabled"
            >
        </div>
        
        <!-- Error Message -->
        <div v-if="error" class="text-red-400 text-xs mt-2 flex items-center gap-1.5 animate-fade-in-up">
            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <span>{{ error }}</span>
        </div>
    </div>
</template>

<script setup>
defineProps({
    modelValue: [String, Number],
    label: String,
    id: {
        type: String,
        default: () => `input-${Math.random().toString(36).substring(2, 9)}`
    },
    type: {
        type: String,
        default: 'text',
    },
    placeholder: String,
    error: String,
    required: {
        type: Boolean,
        default: false,
    },
    disabled: {
        type: Boolean,
        default: false,
    }
});

defineEmits(['update:modelValue']);
</script>
