<script setup>
import { ref, computed, onMounted } from "vue";
import CrossSvgIcon from "../../assets/icons/cross-svg-icon.vue";
import Loader from "../../components/shared/loader/Loader.vue";
import { useTaxStore } from "./taxStore";

const props = defineProps(["tax_id"]);
const emit = defineEmits(["close"]);

const loading = ref(false);
const taxStore = useTaxStore();
const tax_data = computed(() => taxStore.current_tax_item);

async function fetchData(id) {
    loading.value = true;
    await taxStore.fetchTax(id);
    loading.value = false;
}

async function closeViewTaxModal() {
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
                    <h5 class="modal-title">Tax Details</h5>
                    <button type="button" class="close">
                        <CrossSvgIcon @click="closeViewTaxModal" />
                    </button>
                </div>
                <div class="modal-body pb-5">
                    <Loader v-if="loading" />
                    <div class="form-items" v-if="loading == false">
                        <form action="">
                            <div class="form-item">
                                <label class="my-2">Tax Name</label>
                                <input
                                    disabled
                                    type="text"
                                    class="form-control"
                                    v-model="tax_data.name"
                                />
                            </div>
                            <div class="form-item">
                                <label class="my-2">Tax Rate</label>
                                <input
                                    disabled
                                    type="text"
                                    class="form-control"
                                    v-model="tax_data.rate"
                                />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
