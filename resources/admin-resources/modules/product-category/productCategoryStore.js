import axios from "axios";
import formatValidationErrors from "../../utils/format-validation-errors";
import { defineStore } from "pinia";
import { useNotificationStore } from "../../components/shared/notification/notificationStore";

export const useProductCategoryStore = defineStore("product_category", {
    state: () => ({
        current_page: 1,
        total_pages: 0,
        per_page: 10,

        q_name: "",

        product_categories: [],

        edit_product_category_id: null,
        view_product_category_id: null,

        add_product_category_errors: {},

        edit_product_category_errors: {},

        current_product_category_item: {
            id: "",
            name: "",
            slug: "",
            thumbnail: [],
        },
    }),

    getters: {},

    actions: {
        fetchProductCategories(page, per_page, q_name) {
            return new Promise((resolve, reject) => {
                axios
                    .get(
                        `/api/product-categories?page=${page}&per_page=${per_page}&q_name=${q_name}`
                    )
                    .then((response) => {
                        this.product_categories = response.data.data;
                        if (response.data.meta) {
                            this.total_pages = response.data.meta.last_page;
                            this.current_page = response.data.meta.current_page;
                            this.per_page = response.data.meta.per_page;
                            this.q_name = q_name;
                        }
                        resolve(this.product_categories);
                    })
                    .catch((errors) => {
                        reject(errors);
                    });
            });
        },

        fetchProductCategoryList() {
            return new Promise((resolve, reject) => {
                axios
                    .get(`/api/product-categories`)
                    .then((response) => {
                        resolve(response.data.data);
                    })
                    .catch((errors) => {
                        reject(errors);
                    });
            });
        },

        async fetchProductCategory(id) {
            return new Promise((resolve, reject) => {
                axios
                    .get(`/api/product-categories/${id}`)
                    .then((response) => {
                        this.current_product_category_item = response.data.data;
                        resolve(response.data.data);
                    })
                    .catch((errors) => {
                        reject(errors);
                    });
            });
        },

        async addProductCategory(data) {
            return new Promise((resolve, reject) => {
                data.thumbnail = data.thumbnail.map((jsonObj) => jsonObj["id"]);
                axios
                    .post(`/api/product-categories`, data)
                    .then((response) => {
                        this.resetCurrentProductCategoryData();
                        const notifcationStore = useNotificationStore();
                        notifcationStore.pushNotification({
                            message: "Product Category Added Successfully",
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
                            this.add_product_category_errors =
                                formatValidationErrors(
                                    error.response.data.errors
                                );
                        }
                        reject(error);
                    });
            });
        },

        async editProductCategory(data) {
            return new Promise((resolve, reject) => {
                data.thumbnail = data.thumbnail.map((jsonObj) => jsonObj["id"]);
                axios
                    .put(
                        `/api/product-categories/${this.edit_product_category_id}`,
                        data
                    )
                    .then((response) => {
                        this.resetCurrentProductCategoryData();
                        const notifcationStore = useNotificationStore();
                        notifcationStore.pushNotification({
                            message: "product category updated successfully",
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
                            this.edit_product_category_errors =
                                formatValidationErrors(
                                    errors.response.data.errors
                                );
                        }
                        reject(errors);
                    });
            });
        },

        async deleteProductCategory(id) {
            return new Promise((resolve, reject) => {
                axios
                    .delete(`/api/product-categories/${id}`)
                    .then((response) => {
                        if (
                            this.product_categories.length == 1 ||
                            (Array.isArray(id) &&
                                id.length == this.product_categories.length)
                        ) {
                            this.current_page == 1
                                ? (this.current_page = 1)
                                : (this.current_page -= 1);
                        }

                        this.resetCurrentProductCategoryData();
                        const notifcationStore = useNotificationStore();
                        notifcationStore.pushNotification({
                            message: "product category deleted successfully",
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

        resetCurrentProductCategoryData() {
            this.current_product_category_item = {
                id: "",
                name: "",
                slug: "",
                thumbnail: [],
            };
            this.add_product_category_errors = [];
            this.edit_product_category_errors = [];
        },
    },
});
