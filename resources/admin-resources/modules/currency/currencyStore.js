import axios from "axios";
import formatValidationErrors from "../../utils/format-validation-errors";
import { defineStore } from "pinia";
import { ref } from "vue";
import { useNotificationStore } from "../../components/shared/notification/notificationStore";

export const useCurrencyStore = defineStore("currency", {
    state: () => ({
        current_page: 1,
        total_pages: 0,
        per_page: 10,

        q_name: "",

        currencies: [],

        edit_currency_id: null,
        view_currency_id: null,

        add_currency_errors: {},

        edit_currency_errors: {},

        current_currency_item: {
            id: "",
            name: "",
            code: "",
            symbol: "",
        },
    }),

    getters: {
        //getClasses: state => state.classes
    },

    actions: {
        fetchCurrencies(page, per_page, q_name) {
            return new Promise((resolve, reject) => {
                axios
                    .get(
                        `/api/currencies?page=${page}&per_page=${per_page}&q_name=${q_name}`
                    )
                    .then((response) => {
                        this.currencies = response.data.data;
                        if (response.data.meta) {
                            this.total_pages = response.data.meta.last_page;
                            this.current_page = response.data.meta.current_page;
                            this.per_page = response.data.meta.per_page;
                            this.q_name = q_name;
                        }
                        resolve(this.currencies);
                    })
                    .catch((errors) => {
                        reject(errors);
                    });
            });
        },

        async fetchCurrency(id) {
            return new Promise((resolve, reject) => {
                axios
                    .get(`/api/currencies/${id}`)
                    .then((response) => {
                        this.current_currency_item = response.data.data;
                        resolve(response.data.data);
                    })
                    .catch((errors) => {
                        reject(errors);
                    });
            });
        },

        async addCurrency(data) {
            return new Promise((resolve, reject) => {
                axios
                    .post(`/api/currencies`, data)
                    .then((response) => {
                        this.resetCurrentCurrencyData();
                        const notifcationStore = useNotificationStore();
                        notifcationStore.pushNotification({
                            message: "Currency Added Successfully",
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
                            this.add_currency_errors = formatValidationErrors(
                                error.response.data.errors
                            );
                        }
                        reject(error);
                    });
            });
        },

        async editCurrency(data) {
            return new Promise((resolve, reject) => {
                axios
                    .put(`/api/currencies/${this.edit_currency_id}`, data)
                    .then((response) => {
                        this.resetCurrentCurrencyData();
                        const notifcationStore = useNotificationStore();
                        notifcationStore.pushNotification({
                            message: "currency updated successfully",
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
                            this.edit_currency_errors = formatValidationErrors(
                                errors.response.data.errors
                            );
                        }
                        reject(errors);
                    });
            });
        },

        async deleteCurrency(id) {
            return new Promise((resolve, reject) => {
                axios
                    .delete(`/api/currencies/${id}`)
                    .then((response) => {
                        if (
                            this.currencies.length == 1 ||
                            (Array.isArray(id) &&
                                id.length == this.currencies.length)
                        ) {
                            this.current_page == 1
                                ? (this.current_page = 1)
                                : (this.current_page -= 1);
                        }

                        this.fetchCurrencies(
                            this.current_page,
                            this.per_page,
                            this.q_name
                        );

                        this.resetCurrentCurrencyData();
                        const notifcationStore = useNotificationStore();
                        notifcationStore.pushNotification({
                            message: "currency deleted successfully",
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

        resetCurrentCurrencyData() {
            this.current_currency_item = {
                id: "",
                name: "",
                code: "",
                symbol: "",
            };
            this.add_currency_errors = [];
            this.edit_currency_errors = [];
        },
    },
});
