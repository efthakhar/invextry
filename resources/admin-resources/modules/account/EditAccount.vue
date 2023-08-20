<script setup>
import { ref, computed, onMounted } from "vue";
import CrossSvgIcon from "../../assets/icons/cross-svg-icon.vue";
import Loader from "../../components/shared/loader/Loader.vue";
import { useAccountStore } from "./accountStore";

const props = defineProps(["account_id"]);
const emit = defineEmits(["close", "refreshData"]);

const loading = ref(false);
const accountStore = useAccountStore();
const account_data = computed(() => accountStore.current_account_item);

async function submitData() {
    accountStore
        .editAccount(
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

async function fetchData(id) {
    loading.value = true;
    await accountStore.fetchAccount(id);
    loading.value = false;
}

async function closeEditAccountModal() {
    accountStore.resetCurrentAccountData();
    emit("close");
}

onMounted(() => {
    fetchData(props.account_id);
});
</script>

<template>
    <div class="modal fade show d-block">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Account</h5>
                    <button type="button" class="close">
                        <CrossSvgIcon @click="closeEditAccountModal" />
                    </button>
                </div>

                <div class="modal-body">
                    <Loader v-if="loading" />
                    <div class="form-items" v-if="loading == false">
                        <form action="">
                            <div class="form-item mt-4">
                                <label class="my-2">Staus</label>
                                <p
                                    class="text-danger"
                                    v-if="
                                        accountStore.edit_account_errors.status
                                    "
                                >
                                    {{ accountStore.edit_account_errors.status }}
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
                                    v-if="accountStore.edit_account_errors.name"
                                >
                                    {{ accountStore.edit_account_errors.name }}
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
                                    v-if="
                                        accountStore.edit_account_errors
                                            .bank_name
                                    "
                                >
                                    {{
                                        accountStore.edit_account_errors
                                            .bank_name
                                    }}
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
                                        accountStore.edit_account_errors
                                            .branch_name
                                    "
                                >
                                    {{
                                        accountStore.edit_account_errors
                                            .branch_name
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
                                        accountStore.edit_account_errors
                                            .account_number
                                    "
                                >
                                    {{
                                        accountStore.edit_account_errors
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
                                    v-if="
                                        accountStore.edit_account_errors.balance
                                    "
                                >
                                    {{
                                        accountStore.edit_account_errors.balance
                                    }}
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
                </div>

                <div class="modal-footer">
                    <button
                        class="btn btn-danger btn-sm"
                        @click="closeEditAccountModal"
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
