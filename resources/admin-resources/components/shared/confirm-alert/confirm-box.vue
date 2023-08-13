<script setup>
import { onBeforeUnmount, onMounted, ref, watch } from "@vue/runtime-core";
import { useConfirmStore } from "./confirmStore";
const props = defineProps(["data"]);
const emit = defineEmits(["close"]);
const confirmStore = useConfirmStore();
</script>

<template>
    <div>
        <div class="confirm_box_container">
            <div class="confirm_box">
                <p>{{ confirmStore.message }}</p>
                <div>
                    <a
                        class="btn btn-danger me-2"
                        @click="confirmStore.confirm_action()"
                        >yes</a
                    >
                    <a
                        class="btn btn-primary"
                        @click="confirmStore.cancel_action()"
                        >cancel</a
                    >
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.confirm_box_container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background-color: rgba(0, 0, 0, 0.315);
    z-index: 1000;
    display: flex;
    justify-content: center;
    align-items: center;
}
.confirm_box {
    background-color: white !important;
    box-shadow: 1px 1px 12px grey;
    text-align: center;
    width: 400px;
    max-width: 90%;
    min-height: 180px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    overflow: hidden;
    z-index: 1000;
    border-radius: 5px;
    padding: 10px 30px;
}
</style>
