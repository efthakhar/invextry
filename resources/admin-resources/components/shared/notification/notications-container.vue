<script setup>
import { useNotificationStore } from "./notificationStore";
import notification from "./notification.vue";
import { ref } from "vue";
const notificationStore = useNotificationStore();
const notifications = ref(notificationStore.notifications);
</script>

<template>
    <div
        class="notifications_container"
        v-if="notificationStore.notifications.length > 0"
    >
        <notification
            v-for="notification_item in notifications"
            :key="notification_item.id"
            :data="notification_item"
            @close="notificationStore.hideNotification(notification_item)"
        />
    </div>
</template>

<style>
.notifications_container {
    position: fixed;
    bottom: 2%;
    right: 5%;
    z-index: 10000;
}
</style>
