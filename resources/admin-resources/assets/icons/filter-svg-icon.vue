<script setup>
defineProps({
    color: String,
    background: String,
    width: String,
    height: String,
});
</script>

<template>
    <svg
        :width="width ? width : '24px'"
        :height="height ? height : '24px'"
        :fill="color ? color : 'black'"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 24 24"
    >
        <path d="M10 14L4 5V3H20V5L14 14V20L10 22V14Z"></path>
    </svg>
</template>
