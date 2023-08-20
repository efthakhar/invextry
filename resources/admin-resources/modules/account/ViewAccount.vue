<script setup>
import { ref, computed, onMounted } from "vue";
import CrossSvgIcon from "../../assets/icons/cross-svg-icon.vue";
import Loader from "../../components/shared/loader/Loader.vue";
import { useAccountStore } from "./accountStore";

const props = defineProps(["account_id"]);
const emit = defineEmits(["close"]);

const loading = ref(false);
const accountStore = useAccountStore();
const account_data = computed(() => accountStore.current_account_item);

async function fetchData(id) {
    loading.value = true;
    await accountStore.fetchAccount(id);
    loading.value = false;
}

async function closeViewAccountModal() {
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
                    <h5 class="modal-title">Account Details</h5>
                    <button type="button" class="close">
                        <CrossSvgIcon @click="closeViewAccountModal" />
                    </button>
                </div>

                <div class="modal-body">
                    <Loader v-if="loading" />
                    <div class="form-items" v-if="loading == false">
                        <form action="">
                            <div class="form-item mt-4">
                                <label class="my-2">Staus</label>

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
                                <input
                                    disabled
                                    type="text"
                                    class="form-control"
                                    v-model="account_data.name"
                                />
                            </div>
                            <div class="form-item">
                                <label class="my-2">Bank Name</label>
                                <input
                                    disabled
                                    type="text"
                                    class="form-control"
                                    v-model="account_data.bank_name"
                                />
                            </div>
                            <div class="form-item">
                                <label class="my-2">Branch Name</label>
                                <input
                                    disabled
                                    type="text"
                                    class="form-control"
                                    v-model="account_data.branch_name"
                                />
                            </div>
                            <div class="form-item">
                                <label class="my-2">Account Number</label>
                                <input
                                disabled
                                    type="text" 
                                    class="form-control"
                                    v-model="account_data.account_number"
                                />
                            </div>
                            <div class="form-item">
                                <label class="my-2">Balance</label>
                                <input
                                disabled
                                    type="number"
                                    min="0"
                                    class="form-control"
                                    v-model="account_data.balance"
                                />
                            </div>
                            <div class="form-item mt-4">
                                <label class="my-2">Details</label>
                                <textarea disabled
                                    v-model="account_data.details"
                                    class="form-control"
                                    rows="3"
                                ></textarea>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
