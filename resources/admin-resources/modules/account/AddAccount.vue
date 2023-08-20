<script setup>
import { computed, onMounted } from "vue";
import CrossSvgIcon from "../../assets/icons/cross-svg-icon.vue";
import { useAccountStore } from "./accountStore";

const emit = defineEmits(["close", "refreshData"]);

const accountStore = useAccountStore();
const account_data = computed(() => accountStore.current_account_item);

async function submitData() {
    accountStore
        .addAccount(
            JSON.parse(JSON.stringify(accountStore.current_account_item))
        )
        .then(() => {
            emit("refreshData");
            emit("close");
        })
        .catch((error) => {
            console.log("error occurred");
        });
}

async function closeAddAccountModal() {
    accountStore.resetCurrentAccountData();
    emit("close");
}

onMounted(() => {
    accountStore.resetCurrentAccountData();
});
</script>

<template>
    <div class="modal fade show d-block">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Account</h5>
                    <button type="button" class="close">
                        <CrossSvgIcon @click="closeAddAccountModal" />
                    </button>
                </div>

                <div class="modal-body">
                    <form action="">
                        <div class="form-item mt-4">
                            <label class="my-2">Staus</label>
                            <p
                                class="text-danger"
                                v-if="accountStore.add_account_errors.status"
                            >
                                {{ accountStore.add_account_errors.status }}
                            </p>
                            <div class="d-flex">
                                <span class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        v-model="account_data.status"
                                        id="active"
                                        value="active"
                                    />
                                    <label
                                        class="form-check-label"
                                        for="active"
                                    >
                                        Active
                                    </label>
                                </span>
                                <span class="form-check ms-2">
                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        v-model="account_data.status"
                                        id="disabled"
                                        value="disabled"
                                    />
                                    <label
                                        class="form-check-label"
                                        for="disabled"
                                    >
                                        Disabled
                                    </label>
                                </span>
                            </div>
                        </div>
                        <div class="form-item">
                            <label class="my-2">Name</label>
                            <p
                                class="text-danger"
                                v-if="accountStore.add_account_errors.name"
                            >
                                {{ accountStore.add_account_errors.name }}
                            </p>
                            <input
                                type="text"
                                class="form-control"
                                v-model="account_data.name"
                            />
                        </div>
                        <div class="form-item">
                            <label class="my-2">Bank Name</label>
                            <p
                                class="text-danger"
                                v-if="accountStore.add_account_errors.bank_name"
                            >
                                {{ accountStore.add_account_errors.bank_name }}
                            </p>
                            <input
                                type="text"
                                class="form-control"
                                v-model="account_data.bank_name"
                            />
                        </div>
                        <div class="form-item">
                            <label class="my-2">Branch Name</label>
                            <p
                                class="text-danger"
                                v-if="
                                    accountStore.add_account_errors.branch_name
                                "
                            >
                                {{
                                    accountStore.add_account_errors.branch_name
                                }}
                            </p>
                            <input
                                type="text"
                                class="form-control"
                                v-model="account_data.branch_name"
                            />
                        </div>
                        <div class="form-item">
                            <label class="my-2">Account Number</label>
                            <p
                                class="text-danger"
                                v-if="
                                    accountStore.add_account_errors
                                        .account_number
                                "
                            >
                                {{
                                    accountStore.add_account_errors
                                        .account_number
                                }}
                            </p>
                            <input
                                type="text"
                                class="form-control"
                                v-model="account_data.account_number"
                            />
                        </div>
                        <div class="form-item">
                            <label class="my-2">Balance</label>
                            <p
                                class="text-danger"
                                v-if="accountStore.add_account_errors.balance"
                            >
                                {{ accountStore.add_account_errors.balance }}
                            </p>
                            <input
                                type="number"
                                min="0"
                                class="form-control"
                                v-model="account_data.balance"
                            />
                        </div>
                        <div class="form-item mt-4">
                            <label class="my-2">Details</label>
                            <textarea
                                v-model="account_data.details"
                                class="form-control"
                                rows="3"
                            ></textarea>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button
                        class="btn btn-danger btn-sm"
                        @click="closeAddAccountModal"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="btn btn-primary ml-1 btn-sm"
                        @click="submitData"
                    >
                        Save
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
