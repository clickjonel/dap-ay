<template>
    <FloatLabel variant="on" :class="props.width">
        <InputText v-model="model" class="w-full" @change="emit('change')" :size="props.size" :disabled="props.disabled"/>
        <label>{{ props.label }}</label>
    </FloatLabel>
</template>

<script setup>
    import { FloatLabel,InputText } from 'primevue';

    const model = defineModel()
    const props = defineProps({
        label:{
            type:String,
            default:'Label'
        },
        width:{
            type:String,
            default:'w-full'
        },
        size:{
            type:String,
            default:''
        },
        disabled:{
            type:Boolean,
            default:false
        }
    })
    const emit = defineEmits(['change'])

</script>