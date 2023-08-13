import { defineStore } from "pinia";

export const useSidebar = defineStore("sidebar", {
    state: () => ({
        open: true,
    }),

    getters: {
        getSidebarStatus: (state) => state.open,
    },

    actions: {
        toggle() {
            this.open = !this.open;
        },
    },
});
