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
        :width="width ? width : '30px'"
        :height="height ? height : '30px'"
        :fill="color ? color : '#757779'"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 24 24"
    >
    <path d="M20.0049 2C21.1068 2 22 2.89821 22 3.9908V20.0092C22 21.1087 21.1074 22 20.0049 22H4V18H2V16H4V13H2V11H4V8H2V6H4V2H20.0049ZM8 4H6V20H8V4ZM20 4H10V20H20V4Z"></path>
    </svg>
</template>
