<script setup>
import { computed, onMounted } from "vue";
import CrossSvgIcon from "../../assets/icons/cross-svg-icon.vue";
import { useTaxStore } from "./taxStore";

const emit = defineEmits(["close", "refreshData"]);

const taxStore = useTaxStore();
const tax_data = computed(() => taxStore.current_tax_item);

async function submitData() {
    taxStore
        .addTax(taxStore.current_tax_item)
        .then(() => {
            emit("refreshData");
            emit("close");
        })
        .catch((error) => {
            console.log("error occurred");
        });
}

async function closeAddTaxModal() {
    taxStore.resetCurrentTaxData();
    emit("close");
}

onMounted(() => {
    taxStore.resetCurrentTaxData();
});
</script>

<template>
    <div class="modal fade show d-block">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Tax</h5>
                    <button type="button" class="close">
                        <CrossSvgIcon @click="closeAddTaxModal" />
                    </button>
                </div>

                <div class="modal-body">
                    <form action="">
                        <div class="form-item">
                            <label class="my-2">Tax Name</label>
                            <p
                                class="text-danger"
                                v-if="taxStore.add_tax_errors.name"
                            >
                                {{ taxStore.add_tax_errors.name }}
                            </p>
                            <input
                                type="text"
                                class="form-control"
                                v-model="tax_data.name"
                            />
                        </div>
                        <div class="form-item">
                            <label class="my-2">Tax Rate</label>
                            <p
                                class="text-danger"
                                v-if="taxStore.add_tax_errors.rate"
                            >
                                {{ taxStore.add_tax_errors.rate }}
                            </p>
                            <input
                                type="number"
                                class="form-control"
                                v-model="tax_data.rate"
                            />
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button
                        class="btn btn-danger btn-sm"
                        @click="closeAddTaxModal"
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
