import axios from "axios";
import formatValidationErrors from "../../utils/format-validation-errors";
import { defineStore } from "pinia";
import { useNotificationStore } from "../../components/shared/notification/notificationStore";

export const useProductStore = defineStore("product", {
    state: () => ({
        current_page: 1,
        total_pages: 0,
        per_page: 10,

        q_name: "",

        products: [],

        edit_product_id: "",
        view_product_id: "",

        add_product_errors: {},

        edit_product_errors: {},

        current_product_item: {
            id: "",
            code: "",
            name: "",
            slug: "",
            product_type: "",
            barcode_symbology: "CODE128",
            stock_alert_quantity: "",
            stock_quantity: "",
            purchase_price: "",
            sale_price: "",
            parent_id: 7,
            brand_id: "",
            brand: {
                id: "",
                name: "",
            },
            category_id: "",
            category: {
                id: "",
                name: "",
            },
            unit_id: "",
            unit: {
                id: "",
                name: "",
            },
            purchase_unit_id: "",
            purchase_unit: {
                id: "",
                name: "",
            },
            sale_unit_id: "",
            sale_unit: {
                id: "",
                name: "",
            },
            tax_id: "",
            tax: {
                id: "",
                name: "",
            },
            tax_type: "exclusive",
            description: "",
            gallery: [],
        },
    }),

    getters: {},

    actions: {
        fetchProducts(page, per_page, q_name) {
            return new Promise((resolve, reject) => {
                axios
                    .get(
                        `/api/products?page=${page}&per_page=${per_page}&q_name=${q_name}`
                    )
                    .then((response) => {
                        this.products = response.data.data;
                        if (response.data.meta) {
                            this.total_pages = response.data.meta.last_page;
                            this.current_page = response.data.meta.current_page;
                            this.per_page = response.data.meta.per_page;
                            this.q_name = q_name;
                        }
                        resolve(this.products);
                    })
                    .catch((errors) => {
                        reject(errors);
                    });
            });
        },

        async fetchProduct(id) {
            return new Promise((resolve, reject) => {
                axios
                    .get(`/api/products/${id}`)
                    .then((response) => {
                        this.current_product_item = response.data.data;
                        resolve(response.data.data);
                    })
                    .catch((errors) => {
                        reject(errors);
                    });
            });
        },

        async addProduct(data) {
            return new Promise((resolve, reject) => {
                data.gallery = data.gallery.map((jsonObj) => jsonObj["id"]);
                axios
                    .post(`/api/products`, data)
                    .then((response) => {
                        this.resetCurrentProductData();
                        const notifcationStore = useNotificationStore();
                        notifcationStore.pushNotification({
                            message: "Product Added Successfully",
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
                            this.add_product_errors = formatValidationErrors(
                                error.response.data.errors
                            );
                        }
                        reject(error);
                    });
            });
        },

        async editProduct(data) {
            return new Promise((resolve, reject) => {
                data.gallery = data.gallery.map((jsonObj) => jsonObj["id"]);
                axios
                    .put(`/api/products/${this.edit_product_id}`, data)
                    .then((response) => {
                        this.resetCurrentProductData();
                        const notifcationStore = useNotificationStore();
                        notifcationStore.pushNotification({
                            message: "product updated successfully",
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
                            this.edit_product_errors = formatValidationErrors(
                                errors.response.data.errors
                            );
                        }
                        reject(errors);
                    });
            });
        },

        async deleteProduct(id) {
            return new Promise((resolve, reject) => {
                axios
                    .delete(`/api/products/${id}`)
                    .then((response) => {
                        if (
                            this.products.length == 1 ||
                            (Array.isArray(id) &&
                                id.length == this.products.length)
                        ) {
                            this.current_page == 1
                                ? (this.current_page = 1)
                                : (this.current_page -= 1);
                        }

                        this.resetCurrentProductData();
                        const notifcationStore = useNotificationStore();
                        notifcationStore.pushNotification({
                            message: "product deleted successfully",
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

        resetCurrentProductData() {
            this.current_product_item = {
                id: "",
                code: "",
                name: "",
                slug: "",
                product_type: "",
                barcode_symbology: "CODE128",
                stock_alert_quantity: "",
                stock_quantity: "",
                purchase_price: "",
                sale_price: "",
                parent_id: 7,
                brand_id: "",
                brand: {
                    id: "",
                    name: "",
                },
                category_id: "",
                category: {
                    id: "",
                    name: "",
                },
                unit_id: "",
                unit: {
                    id: "",
                    name: "",
                },
                purchase_unit_id: "",
                purchase_unit: {
                    id: "",
                    name: "",
                },
                sale_unit_id: "",
                sale_unit: {
                    id: "",
                    name: "",
                },
                tax_id: "",
                tax: {
                    id: "",
                    name: "",
                },
                tax_type: "exclusive",
                description: "",
                gallery: [],
            };
            this.add_product_errors = [];
            this.edit_product_errors = [];
        },
    },
});
