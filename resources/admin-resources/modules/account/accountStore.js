import axios from "axios";
import formatValidationErrors from "../../utils/format-validation-errors";
import { defineStore } from "pinia";
import { useNotificationStore } from "../../components/shared/notification/notificationStore";

export const useAccountStore = defineStore("account", {
    state: () => ({
        current_page: 1,
        total_pages: 0,
        per_page: 10,

        q_name: "",
        q_status: "",

        accounts: [],

        edit_account_id: null,
        view_account_id: null,

        add_account_errors: {},

        edit_account_errors: {},

        current_account_item: {
            id: null,
            name: null,
            bank_name: null,
            branch_name: null,
            account_number: null,
            balance: 0,
            details: null,
            status: "active",
        },
    }),

    getters: {},

    actions: {
        fetchAccounts(page, per_page, q_name, q_status) {
            return new Promise((resolve, reject) => {
                axios
                    .get(
                        `/api/accounts?page=${page}&per_page=${per_page}&q_name=${q_name}&q_status=${q_status}`
                    )
                    .then((response) => {
                        this.accounts = response.data.data;
                        if (response.data.meta) {
                            this.total_pages = response.data.meta.last_page;
                            this.current_page = response.data.meta.current_page;
                            this.per_page = response.data.meta.per_page;
                            this.q_name = q_name;
                        }
                        resolve(this.accounts);
                    })
                    .catch((errors) => {
                        reject(errors);
                    });
            });
        },

        async fetchAccount(id) {
            return new Promise((resolve, reject) => {
                axios
                    .get(`/api/accounts/${id}`)
                    .then((response) => {
                        this.current_account_item = response.data.data;
                        resolve(response.data.data);
                    })
                    .catch((errors) => {
                        reject(errors);
                    });
            });
        },

        async addAccount(data) {
            return new Promise((resolve, reject) => {
                axios
                    .post(`/api/accounts`, data)
                    .then((response) => {
                        this.resetCurrentAccountData();
                        const notifcationStore = useNotificationStore();
                        notifcationStore.pushNotification({
                            message: "Account Added Successfully",
                            type: "success",
                            time: 2000,
                        });

                        resolve();
                    })
                    .catch((error) => {
                        const notifcationStore = useNotificationStore();
                        notifcationStore.pushNotification({
                            message: "Error Occurred",
                            type: "error",
                            time: 2000,
                        });

                        if (error.response.status == 422) {
                            this.add_account_errors = formatValidationErrors(
                                error.response.data.errors
                            );
                        }
                        reject(error);
                    });
            });
        },

        async editAccount(data) {
            return new Promise((resolve, reject) => {
                axios
                    .put(`/api/accounts/${this.edit_account_id}`, data)
                    .then((response) => {
                        this.resetCurrentAccountData();
                        const notifcationStore = useNotificationStore();
                        notifcationStore.pushNotification({
                            message: "account updated successfully",
                            type: "success",
                        });
                        resolve(response);
                    })
                    .catch((errors) => {
                        const notifcationStore = useNotificationStore();
                        notifcationStore.pushNotification({
                            message: "Error Occurred",
                            type: "error",
                        });

                        if (errors.response.status == 422) {
                            this.edit_account_errors = formatValidationErrors(
                                errors.response.data.errors
                            );
                        }
                        reject(errors);
                    });
            });
        },

        deleteAccount(id) {
            return new Promise((resolve, reject) => {
                axios
                    .delete(`/api/accounts/${id}`)
                    .then((response) => {
                        if (
                            this.accounts.length == 1 ||
                            (Array.isArray(id) &&
                                id.length == this.accounts.length)
                        ) {
                            this.current_page == 1
                                ? (this.current_page = 1)
                                : (this.current_page -= 1);
                        }

                        this.resetCurrentAccountData();
                        const notifcationStore = useNotificationStore();
                        notifcationStore.pushNotification({
                            message: "accounts deleted successfully",
                            type: "success",
                            time: 2000,
                        });

                        resolve(response);
                    })
                    .catch((error) => {
                        reject(error);
                    });
            });
        },

        resetCurrentAccountData() {
            this.current_account_item = {
                id: null,
                name: null,
                bank_name: null,
                branch_name: null,
                account_number: null,
                balance: 0,
                details: null,
                status: "active",
            };
            this.add_account_errors = [];
            this.edit_account_errors = [];
        },
    },
});
