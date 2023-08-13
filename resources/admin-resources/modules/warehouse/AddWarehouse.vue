<script setup>
import { computed, onMounted } from "vue";
import CrossSvgIcon from "../../assets/icons/cross-svg-icon.vue";
import { useWarehouseStore } from "./warehouseStore";

const emit = defineEmits(["close", "refreshData"]);

const warehouseStore = useWarehouseStore();
const warehouse_data = computed(() => warehouseStore.current_warehouse_item);

async function submitData() {
    warehouseStore
        .addWarehouse(warehouseStore.current_warehouse_item)
        .then(() => {
            emit("refreshData");
            emit("close");
        })
        .catch((error) => {
            console.log("error occurred");
        });
}

async function closeAddWarehouseModal() {
    warehouseStore.resetCurrentWarehouseData();
    emit("close");
}

onMounted(() => {
    warehouseStore.resetCurrentWarehouseData();
});
</script>

<template>
    <div class="modal fade show d-block">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Warehouse</h5>
                    <button type="button" class="close">
                        <CrossSvgIcon @click="closeAddWarehouseModal" />
                    </button>
                </div>

                <div class="modal-body">
                    <form action="">
                        <div class="form-item">
                            <label class="my-2">Warehouse Name</label>
                            <p
                                class="text-danger"
                                v-if="warehouseStore.add_warehouse_errors.name"
                            >
                                {{ warehouseStore.add_warehouse_errors.name }}
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
                                v-if="warehouseStore.add_warehouse_errors.email"
                            >
                                {{ warehouseStore.add_warehouse_errors.email }}
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
                                v-if="warehouseStore.add_warehouse_errors.phone"
                            >
                                {{ warehouseStore.add_warehouse_errors.phone }}
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
                                v-if="warehouseStore.add_warehouse_errors.address"
                            >
                                {{ warehouseStore.add_warehouse_errors.address }}
                            </p>
                            <textarea class="form-control" rows="5" v-model="warehouse_data.address"></textarea>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button
                        class="btn btn-danger btn-sm"
                        @click="closeAddWarehouseModal"
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
