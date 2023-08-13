<script setup>
import { ref, computed, onMounted } from "vue";
import CrossSvgIcon from "../../assets/icons/cross-svg-icon.vue";
import Loader from "../../components/shared/loader/Loader.vue";
import { useWarehouseStore } from "./warehouseStore";

const props = defineProps(["warehouse_id"]);
const emit = defineEmits(["close", "refreshData"]);

const loading = ref(false);
const warehouseStore = useWarehouseStore();
const warehouse_data = computed(() => warehouseStore.current_warehouse_item);

async function submitData() {
    warehouseStore
        .editWarehouse(warehouseStore.current_warehouse_item)
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
    await warehouseStore.fetchWarehouse(id);
    loading.value = false;
}

async function closeEditWarehouseModal() {
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
                    <h5 class="modal-title">Edit Warehouse</h5>
                    <button type="button" class="close">
                        <CrossSvgIcon @click="closeEditWarehouseModal" />
                    </button>
                </div>

                <div class="modal-body">
                    <Loader v-if="loading" />
                    <div class="form-items" v-if="loading == false">
                        <form action="">
                            <div class="form-item">
                                <label class="my-2">Warehouse Name</label>
                                <p
                                    class="text-danger"
                                    v-if="
                                        warehouseStore.edit_warehouse_errors
                                            .name
                                    "
                                >
                                    {{
                                        warehouseStore.edit_warehouse_errors
                                            .name
                                    }}
                                </p>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="warehouse_data.name"
                                />
                            </div>
                            <div class="form-item">
                                <label class="my-2">Email</label>
                                <p
                                    class="text-danger"
                                    v-if="
                                        warehouseStore.edit_warehouse_errors
                                            .email
                                    "
                                >
                                    {{
                                        warehouseStore.edit_warehouse_errors
                                            .email
                                    }}
                                </p>
                                <input
                                    type="email"
                                    class="form-control"
                                    v-model="warehouse_data.email"
                                />
                            </div>
                            <div class="form-item">
                                <label class="my-2">Phone</label>
                                <p
                                    class="text-danger"
                                    v-if="
                                        warehouseStore.edit_warehouse_errors
                                            .phone
                                    "
                                >
                                    {{
                                        warehouseStore.edit_warehouse_errors
                                            .phone
                                    }}
                                </p>
                                <input
                                    type="tel"
                                    class="form-control"
                                    v-model="warehouse_data.phone"
                                />
                            </div>
                            <div class="form-item">
                                <label class="my-2">Address</label>
                                <p
                                    class="text-danger"
                                    v-if="
                                        warehouseStore.edit_warehouse_errors
                                            .address
                                    "
                                >
                                    {{
                                        warehouseStore.edit_warehouse_errors
                                            .address
                                    }}
                                </p>
                                <textarea
                                    class="form-control"
                                    rows="5"
                                    v-model="warehouse_data.address"
                                ></textarea>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="modal-footer">
                    <button
                        class="btn btn-danger btn-sm"
                        @click="closeEditWarehouseModal"
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
