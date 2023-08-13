import axios from "axios";
import formatValidationErrors from "../../utils/format-validation-errors";
import { defineStore } from "pinia";
import { ref } from "vue";
import { useNotificationStore } from "../../components/shared/notification/notificationStore";

export const useUnitStore = defineStore("unit", {
    state: () => ({
        current_page: 1,
        total_pages: 0,
        per_page: 10,

        q_name: "",

        units: [],
        base_units: [],

        edit_unit_id: null,
        view_unit_id: null,

        add_unit_errors: {},

        edit_unit_errors: {},

        current_unit_item: {
            id: "",
            name: "",
            short_name: "",
            base_unit_id: "",
            operator: "",
            operation_value: "",
        },
    }),

    getters: {},

    actions: {
        fetchUnits(page, per_page, q_name) {
            return new Promise((resolve, reject) => {
                axios
                    .get(
                        `/api/units?page=${page}&per_page=${per_page}&q_name=${q_name}`
                    )
                    .then((response) => {
                        this.units = response.data.data;
                        if (response.data.meta) {
                            this.total_pages = response.data.meta.last_page;
                            this.current_page = response.data.meta.current_page;
                            this.per_page = response.data.meta.per_page;
                            this.q_name = q_name;
                        }
                        resolve(this.units);
                    })
                    .catch((errors) => {
                        reject(errors);
                    });
            });
        },

        fetchBaseUnits() {
            return new Promise((resolve, reject) => {
                axios
                    .get(`/api/base-units`)
                    .then((response) => {
                        this.base_units = response.data.data;
                        resolve(this.base_units);
                    })
                    .catch((errors) => {
                        reject(errors);
                    });
            });
        },
        fetchHomogeneousUnits(baseUnitId) {
            return new Promise((resolve, reject) => {
                axios
                    .get(`/api/homogeneous-units/${baseUnitId}`)
                    .then((response) => {
                        resolve(response.data.data);
                    })
                    .catch((errors) => {
                        reject(errors);
                    });
            });
        },

        async fetchUnit(id) {
            return new Promise((resolve, reject) => {
                axios
                    .get(`/api/units/${id}`)
                    .then((response) => {
                        this.current_unit_item = response.data.data;
                        resolve(response.data.data);
                    })
                    .catch((errors) => {
                        reject(errors);
                    });
            });
        },

        async addUnit(data) {
            // make operator and operation value null if base unit id is null
            if (!data.base_unit_id) {
                data.operator = "";
                data.operation_value = "";
            }

            return new Promise((resolve, reject) => {
                axios
                    .post(`/api/units`, data)
                    .then((response) => {
                        this.resetCurrentUnitData();
                        const notifcationStore = useNotificationStore();
                        notifcationStore.pushNotification({
                            message: "Unit Added Successfully",
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
                            this.add_unit_errors = formatValidationErrors(
                                error.response.data.errors
                            );
                        }
                        reject(error);
                    });
            });
        },

        async editUnit(data) {
            // make operator and operation value null if base unit id is null
            if (!data.base_unit_id) {
                data.operator = "";
                data.operation_value = "";
            }
            return new Promise((resolve, reject) => {
                axios
                    .put(`/api/units/${this.edit_unit_id}`, data)
                    .then((response) => {
                        this.resetCurrentUnitData();
                        const notifcationStore = useNotificationStore();
                        notifcationStore.pushNotification({
                            message: "Unit updated successfully",
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
                            this.edit_unit_errors = formatValidationErrors(
                                errors.response.data.errors
                            );
                        }
                        reject(errors);
                    });
            });
        },

        async deleteUnit(id) {
            return new Promise((resolve, reject) => {
                axios
                    .delete(`/api/units/${id}`)
                    .then((response) => {
                        if (
                            this.units.length == 1 ||
                            (Array.isArray(id) &&
                                id.length == this.units.length)
                        ) {
                            this.current_page == 1
                                ? (this.current_page = 1)
                                : (this.current_page -= 1);
                        }

                        this.fetchUnits(
                            this.current_page,
                            this.per_page,
                            this.q_name
                        );

                        this.resetCurrentUnitData();
                        const notifcationStore = useNotificationStore();
                        notifcationStore.pushNotification({
                            message: "Unit deleted successfully",
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

        resetCurrentUnitData() {
            this.current_unit_item = {
                id: "",
                name: "",
                short_name: "",
                base_unit_id: "",
                operator: "",
                operation_value: "",
            };
            this.add_unit_errors = [];
            this.edit_unit_errors = [];
        },
    },
});
