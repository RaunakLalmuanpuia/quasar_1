<template>
    <p>Media Show</p>
    {{ props.media.id }}
</template>

<script setup>

const props =  defineProps({
    media : Object
})

</script>