<template>
    <FloatLabel variant="on" :class="props.width">
        <InputNumber 
            v-model="model" 
            class="w-full" 
            :size="props.size"
            :min-fraction-digits="props.decimals" 
            :max-fraction-digits="props.decimals"
            :min="props.min"
            :max="props.max"
            :disabled="props.disabled"
            @update:model-value="emit('change', $event)"
        />
        <label>{{ props.label }}</label>
    </FloatLabel>
</template>

<script setup>
    import FloatLabel from 'primevue/floatlabel';
    import InputNumber from 'primevue/inputnumber';

    const model = defineModel();
    
    const props = defineProps({
        label: {
            type: String,
            default: 'Label'
        },
        width: {
            type: String,
            default: 'w-full'
        },
        decimals: {
            type: Number,
            default: 0  // 0 = integer only, 2 = two decimal places, etc.
        },
        min: {
            type: Number,
            default: null
        },
        max: {
            type: Number,
            default: null
        },
        disabled: {
            type: Boolean,
            default: false
        },
         size:{
            type:String,
            default:''
        }
    });
    
    const emit = defineEmits(['change']);
</script>