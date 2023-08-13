import { defineStore } from "pinia";

export const useNotificationStore = defineStore("notification", {
    state: () => ({
        notifications: [],
    }),

    getters: {
        getNotifications: (state) => state.notifications,
    },

    actions: {
        async pushNotification(data) {
            data.id = data.id || Date.now() + Math.random(1, 9);
            data.time = data.time || 3000;
            this.notifications.push(data);
        },

        async hideNotification(data) {
            let index = this.notifications.indexOf(data);
            this.notifications.splice(index, 1);
        },
    },
});
