import axios from "axios";
import formatValidationErrors from "../../utils/format-validation-errors";
import { defineStore } from "pinia";
import { useNotificationStore } from "../../components/shared/notification/notificationStore";

export const useBrandStore = defineStore("brand", {
    state: () => ({
        current_page: 1,
        total_pages: 0,
        per_page: 10,

        q_name: "",

        brands: [],

        edit_brand_id: null,
        view_brand_id: null,

        add_brand_errors: {},

        edit_brand_errors: {},

        current_brand_item: {
            id: "",
            name: "",
            slug: "",
            logo: [],
        },
    }),

    getters: {},

    actions: {

        fetchBrands(page, per_page, q_name) {
            return new Promise((resolve, reject) => {
                axios
                    .get(
                        `/api/brands?page=${page}&per_page=${per_page}&q_name=${q_name}`
                    )
                    .then((response) => {
                        this.brands = response.data.data;
                        if (response.data.meta) {
                            this.total_pages = response.data.meta.last_page;
                            this.current_page = response.data.meta.current_page;
                            this.per_page = response.data.meta.per_page;
                            this.q_name = q_name;
                        }
                        resolve(this.brands);
                    })
                    .catch((errors) => {
                        reject(errors);
                    });
            });
        },

        fetchBrandList() {
            return new Promise((resolve, reject) => {
                axios
                    .get(`/api/brands`)
                    .then((response) => {
                        resolve(response.data.data);
                    })
                    .catch((errors) => {
                        reject(errors);
                    });
            });
        },

        async fetchBrand(id) {
            return new Promise((resolve, reject) => {
                axios
                    .get(`/api/brands/${id}`)
                    .then((response) => {
                        this.current_brand_item = response.data.data;
                        resolve(response.data.data);
                    })
                    .catch((errors) => {
                        reject(errors);
                    });
            });
        },

        async addBrand(data) {
            return new Promise((resolve, reject) => {
                data.logo = data.logo.map((jsonObj) => jsonObj["id"]);
                axios
                    .post(`/api/brands`, data)
                    .then((response) => {
                        this.resetCurrentBrandData();
                        const notifcationStore = useNotificationStore();
                        notifcationStore.pushNotification({
                            message: "Brand Added Successfully",
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
                            this.add_brand_errors = formatValidationErrors(
                                error.response.data.errors
                            );
                        }
                        reject(error);
                    });
            });
        },

        async editBrand(data) {
            return new Promise((resolve, reject) => {
                data.logo = data.logo.map((jsonObj) => jsonObj["id"]);
                axios
                    .put(`/api/brands/${this.edit_brand_id}`, data)
                    .then((response) => {
                        this.resetCurrentBrandData();
                        const notifcationStore = useNotificationStore();
                        notifcationStore.pushNotification({
                            message: "brand updated successfully",
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
                            this.edit_brand_errors = formatValidationErrors(
                                errors.response.data.errors
                            );
                        }
                        reject(errors);
                    });
            });
        },

        async deleteBrand(id) {
            return new Promise((resolve, reject) => {
                axios
                    .delete(`/api/brands/${id}`)
                    .then((response) => {
                        if (
                            this.brands.length == 1 ||
                            (Array.isArray(id) &&
                                id.length == this.brands.length)
                        ) {
                            this.current_page == 1
                                ? (this.current_page = 1)
                                : (this.current_page -= 1);
                        }

                        this.resetCurrentBrandData();
                        const notifcationStore = useNotificationStore();
                        notifcationStore.pushNotification({
                            message: "brand deleted successfully",
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

        resetCurrentBrandData() {
            this.current_brand_item = {
                id: "",
                name: "",
                slug: "",
                logo: [],
            };
            this.add_brand_errors = [];
            this.edit_brand_errors = [];
        },
    },
});
