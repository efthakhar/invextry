<script setup>
import { ref, computed, onMounted } from "vue";
import CrossSvgIcon from "../../assets/icons/cross-svg-icon.vue";
import Loader from "../../components/shared/loader/Loader.vue";
import { useWarehouseStore } from "./warehouseStore";

const props = defineProps(["warehouse_id"]);
const emit = defineEmits(["close"]);

const loading = ref(false);
const warehouseStore = useWarehouseStore();
const warehouse_data = computed(() => warehouseStore.current_warehouse_item);

async function fetchData(id) {
    loading.value = true;
    await warehouseStore.fetchWarehouse(id);
    loading.value = false;
}

async function closeViewWarehouseModal() {
    warehouseStore.resetCurrentWarehouseData();
    emit("close");
}

onMounted(() => {
    fetchData(props.warehouse_id);
});
</script>

<template>
    <div class="modal fade show d-block">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Warehouse Details</h5>
                    <button type="button" class="close">
                        <CrossSvgIcon @click="closeViewWarehouseModal" />
                    </button>
                </div>
                <div class="modal-body pb-5">
                    <Loader v-if="loading" />
                    <div class="form-items" v-if="loading == false">
                        <form action="">
                            <div class="form-item">
                                <label class="my-2">Warehouse Name</label>
                                <input
                                    disabled
                                    type="text"
                                    class="form-control"
                                    v-model="warehouse_data.name"
                                />
                            </div>
                            <div class="form-item">
                                <label class="my-2">Email</label>
                                <input
                                    disabled
                                    type="email"
                                    class="form-control"
                                    v-model="warehouse_data.email"
                                />
                            </div>
                            <div class="form-item">
                                <label class="my-2">Phone</label>
                                <input
                                    disabled
                                    type="tel"
                                    class="form-control"
                                    v-model="warehouse_data.phone"
                                />
                            </div>
                            <div class="form-item">
                                <label class="my-2">Address</label>
                                <textarea
                                    disabled
                                    class="form-control"
                                    rows="5"
                                    v-model="warehouse_data.address"
                                ></textarea>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
