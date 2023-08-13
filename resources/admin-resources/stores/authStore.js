import { defineStore } from "pinia";
import { ref } from "vue";
import axios from "axios";

export const useAuthStore = defineStore("auth", {
    state: () => ({
        fetched: false,
        user: {},
        role: {},
        permissions: [],
    }),

    getters: {},

    actions: {
        async getAuthUser() {
            await axios
                .get(`/api/users/authenticated-user`)
                .then((response) => {
                    this.user = response.data.user;
                    this.role = response.data.role;
                    this.permissions = response.data.permissions;
                })
                .catch((errors) => {});
        },

        userCan(ability) {
            return this.permissions.includes(ability);
        },
    },
});
