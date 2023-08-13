<script setup>
import { onBeforeUnmount, onMounted, ref, watch } from "@vue/runtime-core";
import crossSvgIcon from "../../../assets/icons/cross-svg-icon.vue";
const props = defineProps(["data"]);
const emit = defineEmits(["close"]);
let bar_width = ref(100);
let timer = "";
onMounted(() => {
    setTimeout(() => emit("close"), props.data.time);
    timer = setInterval(() => {
        bar_width.value -= 1;
    }, props.data.time / 100);
});
onBeforeUnmount(() => {
    clearInterval(timer);
});
</script>

<template>
    <div
        class="alert notification_item shadow-lg"
        :class="{
            'alert-success': data.type == 'success',
            'alert-danger': data.type === 'error',
            'alert-warning': data.type === 'warning',
            'alert-info': data.type === 'info',
        }"
    >
        <div class="bar" :style="{ width: `${bar_width}%` }"></div>
        <span class="close_notification_btn" @click="$emit('close')">
            <crossSvgIcon width="16px" height="16px" color="#FF7474" />
        </span>
        <p class="notification-message">{{ data.message }}</p>
    </div>
</template>

<style>
.bar {
    position: absolute;
    bottom: 0px;
    left: 0px;
    background-color: currentColor;
    height: 3px;
}
.notification_item {
    width: 300px;
    overflow: hidden;
}
.close_notification_btn {
    position: absolute;
    top: 5px;
    right: 5px;
    cursor: pointer;
    border-radius: 2px;
    background-color: currentColor;
    width: 20px;
    height: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
}
.notification-message {
    padding-right: 21px;
}
</style>
