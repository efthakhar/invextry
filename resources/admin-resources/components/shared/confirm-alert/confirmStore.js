import { defineStore } from "pinia";

export const useConfirmStore = defineStore("confirm", {
    state: () => ({
        message: "Are you sure to do this action???",
        do_action: false,
        show_confirm_box: false,
        resolve: null,
    }),

    getters: {
        getActionState: (state) => state.do_action,
    },

    actions: {
        async confirm_action() {
            this.do_action = true;
            this.hide_box();
        },

        async cancel_action() {
            this.do_action = false;
            this.hide_box();
        },

        hide_box() {
            this.resolve();
            this.show_confirm_box = false;
        },

        async show_box(data = {}) {
            this.message = data.message ? data.message : this.message;
            this.show_confirm_box = true;
            this.do_action = false;
            return new Promise((resolve, reject) => {
                this.resolve = resolve;
            });
        },
    },
});
