import axios from "axios";
import formatValidationErrors from "../../utils/format-validation-errors";
import { defineStore } from "pinia";
import { useNotificationStore } from "../../components/shared/notification/notificationStore";

export const useSupplierStore = defineStore("supplier", {
    state: () => ({
        current_page: 1,
        total_pages: 0,
        per_page: 10,

        q_name: "",
        q_status: "",

        suppliers: [],

        edit_supplier_id: null,
        view_supplier_id: null,

        add_supplier_errors: {},

        edit_supplier_errors: {},

        current_supplier_item: {
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
        fetchSuppliers(page, per_page, q_name, q_status) {
            return new Promise((resolve, reject) => {
                axios
                    .get(
                        `/api/suppliers?page=${page}&per_page=${per_page}&q_name=${q_name}&q_status=${q_status}`
                    )
                    .then((response) => {
                        this.suppliers = response.data.data;
                        if (response.data.meta) {
                            this.total_pages = response.data.meta.last_page;
                            this.current_page = response.data.meta.current_page;
                            this.per_page = response.data.meta.per_page;
                            this.q_name = q_name;
                        }
                        resolve(this.suppliers);
                    })
                    .catch((errors) => {
                        reject(errors);
                    });
            });
        },

        async fetchSupplier(id) {
            return new Promise((resolve, reject) => {
                axios
                    .get(`/api/suppliers/${id}`)
                    .then((response) => {
                        this.current_supplier_item = response.data.data;
                        resolve(response.data.data);
                    })
                    .catch((errors) => {
                        reject(errors);
                    });
            });
        },

        async addSupplier(data) {
            return new Promise((resolve, reject) => {
                axios
                    .post(`/api/suppliers`, data)
                    .then((response) => {
                        this.resetCurrentSupplierData();
                        const notifcationStore = useNotificationStore();
                        notifcationStore.pushNotification({
                            message: "Supplier Added Successfully",
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
                            this.add_supplier_errors = formatValidationErrors(
                                error.response.data.errors
                            );
                        }
                        reject(error);
                    });
            });
        },

        async editSupplier(data) {
            return new Promise((resolve, reject) => {
                axios
                    .put(`/api/suppliers/${this.edit_supplier_id}`, data)
                    .then((response) => {
                        this.resetCurrentSupplierData();
                        const notifcationStore = useNotificationStore();
                        notifcationStore.pushNotification({
                            message: "supplier updated successfully",
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
                            this.edit_supplier_errors = formatValidationErrors(
                                errors.response.data.errors
                            );
                        }
                        reject(errors);
                    });
            });
        },

        deleteSupplier(id) {
            return new Promise((resolve, reject) => {
                axios
                    .delete(`/api/suppliers/${id}`)
                    .then((response) => {
                        if (
                            this.suppliers.length == 1 ||
                            (Array.isArray(id) &&
                                id.length == this.suppliers.length)
                        ) {
                            this.current_page == 1
                                ? (this.current_page = 1)
                                : (this.current_page -= 1);
                        }

                        this.resetCurrentSupplierData();
                        const notifcationStore = useNotificationStore();
                        notifcationStore.pushNotification({
                            message: "suppliers deleted successfully",
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

        resetCurrentSupplierData() {
            this.current_supplier_item = {
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
            this.add_supplier_errors = [];
            this.edit_supplier_errors = [];
        },
    },
});
