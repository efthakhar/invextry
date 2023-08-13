<script setup>
import { ref, computed, onMounted } from "vue";
import CrossSvgIcon from "../../assets/icons/cross-svg-icon.vue";
import Loader from "../../components/shared/loader/Loader.vue";
import { useTaxStore } from "./taxStore";

const props = defineProps(["tax_id"]);
const emit = defineEmits(["close", "refreshData"]);

const loading = ref(false);
const taxStore = useTaxStore();
const tax_data = computed(() => taxStore.current_tax_item);

async function submitData() {
    taxStore
        .editTax(taxStore.current_tax_item)
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
    await taxStore.fetchTax(id);
    loading.value = false;
}

async function closeEditTaxModal() {
    taxStore.resetCurrentTaxData();
    emit("close");
}

onMounted(() => {
    fetchData(props.tax_id);
});
</script>

<template>
    <div class="modal fade show d-block">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Tax</h5>
                    <button type="button" class="close">
                        <CrossSvgIcon @click="closeEditTaxModal" />
                    </button>
                </div>

                <div class="modal-body">
                    <Loader v-if="loading" />
                    <div class="form-items" v-if="loading == false">
                        <form action="">
                            <div class="form-items">
                                <label class="my-2">Tax Name</label>
                                <p
                                    class="text-danger"
                                    v-if="taxStore.edit_tax_errors.name"
                                >
                                    {{ taxStore.edit_tax_errors.name }}
                                </p>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="tax_data.name"
                                />
                            </div>
                            <div class="form-items">
                                <label class="my-2">Tax Rate</label>
                                <p
                                    class="text-danger"
                                    v-if="taxStore.edit_tax_errors.rate"
                                >
                                    {{ taxStore.edit_tax_errors.rate }}
                                </p>
                                <input
                                    type="number"
                                    class="form-control"
                                    v-model="tax_data.rate"
                                />
                            </div>
                        </form>
                    </div>
                </div>

                <div class="modal-footer">
                    <button
                        class="btn btn-danger btn-sm"
                        @click="closeEditTaxModal"
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
