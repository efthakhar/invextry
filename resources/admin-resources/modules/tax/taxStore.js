import axios from "axios";
import formatValidationErrors from "../../utils/format-validation-errors";
import { defineStore } from "pinia";
import { useNotificationStore } from "../../components/shared/notification/notificationStore";

export const useTaxStore = defineStore("tax", {
    state: () => ({
        current_page: 1,
        total_pages: 0,
        per_page: 10,

        q_name: "",

        taxes: [],

        edit_tax_id: null,
        view_tax_id: null,

        add_tax_errors: {},

        edit_tax_errors: {},

        current_tax_item: {
            id: "",
            name: "",
            rate: "",
        },
    }),

    getters: {},

    actions: {
        fetchTaxes(page, per_page, q_name) {
            return new Promise((resolve, reject) => {
                axios
                    .get(
                        `/api/taxes?page=${page}&per_page=${per_page}&q_name=${q_name}`
                    )
                    .then((response) => {
                        this.taxes = response.data.data;
                        if (response.data.meta) {
                            this.total_pages = response.data.meta.last_page;
                            this.current_page = response.data.meta.current_page;
                            this.per_page = response.data.meta.per_page;
                            this.q_name = q_name;
                        }
                        resolve(this.taxes);
                    })
                    .catch((errors) => {
                        reject(errors);
                    });
            });
        },

        fetchTaxList() {
            return new Promise((resolve, reject) => {
                axios
                    .get(`/api/taxes`)
                    .then((response) => {
                        resolve(response.data.data);
                    })
                    .catch((errors) => {
                        reject(errors);
                    });
            });
        },

        async fetchTax(id) {
            return new Promise((resolve, reject) => {
                axios
                    .get(`/api/taxes/${id}`)
                    .then((response) => {
                        this.current_tax_item = response.data.data;
                        resolve(response.data.data);
                    })
                    .catch((errors) => {
                        reject(errors);
                    });
            });
        },

        async addTax(data) {
            return new Promise((resolve, reject) => {
                axios
                    .post(`/api/taxes`, data)
                    .then((response) => {
                        this.resetCurrentTaxData();
                        const notifcationStore = useNotificationStore();
                        notifcationStore.pushNotification({
                            message: "Tax Added Successfully",
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
                            this.add_tax_errors = formatValidationErrors(
                                error.response.data.errors
                            );
                        }
                        reject(error);
                    });
            });
        },

        async editTax(data) {
            return new Promise((resolve, reject) => {
                axios
                    .put(`/api/taxes/${this.edit_tax_id}`, data)
                    .then((response) => {
                        this.resetCurrentTaxData();
                        const notifcationStore = useNotificationStore();
                        notifcationStore.pushNotification({
                            message: "tax updated successfully",
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
                            this.edit_tax_errors = formatValidationErrors(
                                errors.response.data.errors
                            );
                        }
                        reject(errors);
                    });
            });
        },

        async deleteTax(id) {
            return new Promise((resolve, reject) => {
                axios
                    .delete(`/api/taxes/${id}`)
                    .then((response) => {
                        if (
                            this.taxes.length == 1 ||
                            (Array.isArray(id) &&
                                id.length == this.taxes.length)
                        ) {
                            this.current_page == 1
                                ? (this.current_page = 1)
                                : (this.current_page -= 1);
                        }

                        this.resetCurrentTaxData();
                        const notifcationStore = useNotificationStore();
                        notifcationStore.pushNotification({
                            message: "tax deleted successfully",
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

        resetCurrentTaxData() {
            this.current_tax_item = {
                id: "",
                name: "",
                rate: "",
            };
            this.add_tax_errors = [];
            this.edit_tax_errors = [];
        },
    },
});
