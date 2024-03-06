<template>
    <QuasarLayout>
        <Head title="Note Sheet"/>
        <h3>Note Sheet</h3>
    </QuasarLayout>
    
</template>
<script setup>
import QuasarLayout from "@/Layouts/QuasarLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

const props = defineProps({
    'initialContent' : Object
})
</script>
