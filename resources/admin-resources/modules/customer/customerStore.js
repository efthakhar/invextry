import axios from "axios";
import formatValidationErrors from "../../utils/format-validation-errors";
import { defineStore } from "pinia";
import { useNotificationStore } from "../../components/shared/notification/notificationStore";

export const useCustomerStore = defineStore("customer", {
    state: () => ({
        current_page: 1,
        total_pages: 0,
        per_page: 10,

        q_name: "",
        q_status: "",

        customers: [],

        edit_customer_id: null,
        view_customer_id: null,

        add_customer_errors: {},

        edit_customer_errors: {},

        current_customer_item: {
            id: null,
            name: null,
            phone: null,
            email: null,
            tax_number: null,
            country: null,
            city: null,
            postal_code: null,
            address: null,
            billing_address: null,
            shipping_address: null,
            status: "",
        },
    }),

    getters: {},

    actions: {
        fetchCustomers(page, per_page, q_name, q_status) {
            return new Promise((resolve, reject) => {
                axios
                    .get(
                        `/api/customers?page=${page}&per_page=${per_page}&q_name=${q_name}&q_status=${q_status}`
                    )
                    .then((response) => {
                        this.customers = response.data.data;
                        if (response.data.meta) {
                            this.total_pages = response.data.meta.last_page;
                            this.current_page = response.data.meta.current_page;
                            this.per_page = response.data.meta.per_page;
                            this.q_name = q_name;
                        }
                        resolve(this.customers);
                    })
                    .catch((errors) => {
                        reject(errors);
                    });
            });
        },

        async fetchCustomer(id) {
            return new Promise((resolve, reject) => {
                axios
                    .get(`/api/customers/${id}`)
                    .then((response) => {
                        this.current_customer_item = response.data.data;
                        resolve(response.data.data);
                    })
                    .catch((errors) => {
                        reject(errors);
                    });
            });
        },

        async addCustomer(data) {
            return new Promise((resolve, reject) => {
                axios
                    .post(`/api/customers`, data)
                    .then((response) => {
                        this.resetCurrentCustomerData();
                        const notifcationStore = useNotificationStore();
                        notifcationStore.pushNotification({
                            message: "Customer Added Successfully",
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
                            this.add_customer_errors = formatValidationErrors(
                                error.response.data.errors
                            );
                        }
                        reject(error);
                    });
            });
        },

        async editCustomer(data) {
            return new Promise((resolve, reject) => {
                axios
                    .put(`/api/customers/${this.edit_customer_id}`, data)
                    .then((response) => {
                        this.resetCurrentCustomerData();
                        const notifcationStore = useNotificationStore();
                        notifcationStore.pushNotification({
                            message: "customer updated successfully",
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
                            this.edit_customer_errors = formatValidationErrors(
                                errors.response.data.errors
                            );
                        }
                        reject(errors);
                    });
            });
        },

        deleteCustomer(id) {
            return new Promise((resolve, reject) => {
                axios
                    .delete(`/api/customers/${id}`)
                    .then((response) => {
                        if (
                            this.customers.length == 1 ||
                            (Array.isArray(id) &&
                                id.length == this.customers.length)
                        ) {
                            this.current_page == 1
                                ? (this.current_page = 1)
                                : (this.current_page -= 1);
                        }

                        this.resetCurrentCustomerData();
                        const notifcationStore = useNotificationStore();
                        notifcationStore.pushNotification({
                            message: "customers deleted successfully",
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

        resetCurrentCustomerData() {
            this.current_customer_item = {
                id: null,
                name: null,
                phone: null,
                email: null,
                tax_number: null,
                country: null,
                city: null,
                postal_code: null,
                address: null,
                billing_address: null,
                shipping_address: null,
                status: "",
            };
            this.add_customer_errors = [];
            this.edit_customer_errors = [];
        },
    },
});
