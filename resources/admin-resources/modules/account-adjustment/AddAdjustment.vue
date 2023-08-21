<script setup>
import axios from "axios";
import { computed, onMounted, ref } from "vue";
import CrossSvgIcon from "../../assets/icons/cross-svg-icon.vue";
import { useAdjustmentStore } from "./adjustmentStore";

const emit = defineEmits(["close", "refreshData"]);

const adjustmentStore = useAdjustmentStore();
const adjustment_data = computed(() => adjustmentStore.current_adjustment_item);
const accounts = ref([]);

async function submitData() {
    adjustmentStore
        .addAdjustment(
            JSON.parse(JSON.stringify(adjustmentStore.current_adjustment_item))
        )
        .then(() => {
            emit("refreshData");
            emit("close");
        })
        .catch((error) => {
            console.log("error occurred");
        });
}

async function closeAddAdjustmentModal() {
    adjustmentStore.resetCurrentAdjustmentData();
    emit("close");
}

function fetchAccountList() {
    axios
        .get(`/api/accounts/list`)
        .then((response) => {
            accounts.value = response.data.data;
        })
        .catch((errors) => {});
}

onMounted(() => {
    fetchAccountList();
    adjustmentStore.resetCurrentAdjustmentData();
});
</script>

<template>
    <div class="modal fade show d-block">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Adjustment</h5>
                    <button type="button" class="close">
                        <CrossSvgIcon @click="closeAddAdjustmentModal" />
                    </button>
                </div>

                <div class="modal-body">
                    <form action="">
                        <div class="form-item">
                            <label class="my-2">Account</label>
                            <p
                                class="text-danger"
                                v-if="
                                    adjustmentStore.add_adjustment_errors
                                        .account_id
                                "
                            >
                                {{
                                    adjustmentStore.add_adjustment_errors
                                        .account_id
                                }}
                            </p>
                            <select
                                v-model="adjustment_data.account_id"
                                class="form-select"
                            >
                                <option value="">none</option>
                                <option
                                    :value="account.id"
                                    v-for="account in accounts"
                                >
                                    {{ account.name }}
                                </option>
                            </select>
                        </div>
                        <div class="form-item">
                            <label class="my-2">Amount</label>
                            <p
                                class="text-danger"
                                v-if="
                                    adjustmentStore.add_adjustment_errors.amount
                                "
                            >
                                {{
                                    adjustmentStore.add_adjustment_errors.amount
                                }}
                            </p>
                            <input
                                type="number"
                                min="0"
                                class="form-control"
                                v-model="adjustment_data.amount"
                            />
                        </div>
                        <div class="form-item">
                            <label class="my-2">Type</label>
                            <p
                                class="text-danger"
                                v-if="
                                    adjustmentStore.add_adjustment_errors.type
                                "
                            >
                                {{ adjustmentStore.add_adjustment_errors.type }}
                            </p>
                            <select
                                v-model="adjustment_data.type"
                                class="form-select"
                            >
                                <option value="add">Add</option>
                                <option value="subtract">Subtract</option>
                            </select>
                        </div>
                        <div class="form-item">
                            <label class="my-2">Date</label>
                            <p
                                class="text-danger"
                                v-if="
                                    adjustmentStore.add_adjustment_errors.date
                                "
                            >
                                {{ adjustmentStore.add_adjustment_errors.date }}
                            </p>
                            <input
                                type="date"
                                class="form-control"
                                v-model="adjustment_data.date"
                            />
                        </div>
                        <div class="form-item">
                            <label class="my-2">Note</label>
                            <textarea
                                v-model="adjustment_data.note"
                                class="form-control"
                                rows="3"
                            ></textarea>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button
                        class="btn btn-danger btn-sm"
                        @click="closeAddAdjustmentModal"
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
