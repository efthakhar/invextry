import axios from "axios";
import formatValidationErrors from "../../utils/format-validation-errors";
import { defineStore } from "pinia";
import { useNotificationStore } from "../../components/shared/notification/notificationStore";

export const useAdjustmentStore = defineStore("adjustment", {
    state: () => ({
        current_page: 1,
        total_pages: 0,
        per_page: 10,

        search: "",

        adjustments: [],

        edit_adjustment_id: null,
        view_adjustment_id: null,

        add_adjustment_errors: {},

        edit_adjustment_errors: {},

        current_adjustment_item: {
            id: null,
            account_id: "",
            account: null,
            amount: null,
            type: "add",
            date: null,
            note: null,
        },
    }),

    getters: {},

    actions: {
        fetchAdjustments(page, per_page, search, q_status) {
            return new Promise((resolve, reject) => {
                axios
                    .get(
                        `/api/account-adjustments?page=${page}&per_page=${per_page}&search=${search}`
                    )
                    .then((response) => {
                        this.adjustments = response.data.data;
                        if (response.data.meta) {
                            this.total_pages = response.data.meta.last_page;
                            this.current_page = response.data.meta.current_page;
                            this.per_page = response.data.meta.per_page;
                            this.search = search;
                        }
                        resolve(this.adjustments);
                    })
                    .catch((errors) => {
                        reject(errors);
                    });
            });
        },

        // async fetchAccount(id) {
        //     return new Promise((resolve, reject) => {
        //         axios
        //             .get(`/api/adjustments/${id}`)
        //             .then((response) => {
        //                 this.current_adjustment_item = response.data.data;
        //                 resolve(response.data.data);
        //             })
        //             .catch((errors) => {
        //                 reject(errors);
        //             });
        //     });
        // },

        async addAdjustment(data) {
            return new Promise((resolve, reject) => {
                axios
                    .post(`/api/account-adjustments`, data)
                    .then((response) => {
                        this.resetCurrentAdjustmentData();
                        const notifcationStore = useNotificationStore();
                        notifcationStore.pushNotification({
                            message: "Balance Adjusted Successfully",
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
                            this.add_adjustment_errors = formatValidationErrors(
                                error.response.data.errors
                            );
                        }
                        reject(error);
                    });
            });
        },

        resetCurrentAdjustmentData() {
            this.current_adjustment_item = {
                id: null,
                account_id: "",
                account: null,
                amount: null,
                type: "add",
                date: null,
                note: null,
            };
            this.add_adjustment_errors = [];
            this.edit_adjustment_errors = [];
        },
    },
});
