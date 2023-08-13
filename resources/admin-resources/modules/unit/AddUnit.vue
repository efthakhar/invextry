<script setup>
import { computed, onMounted } from "vue";
import CrossSvgIcon from "../../assets/icons/cross-svg-icon.vue";
import { useUnitStore } from "./unitStore";

const emit = defineEmits(["close", "refreshData"]);

const unitStore = useUnitStore();
const unit_data = computed(() => unitStore.current_unit_item);

async function submitData() {
    unitStore
        .addUnit(unitStore.current_unit_item)
        .then(() => {
            emit("refreshData");
            emit("close");
        })
        .catch((error) => {
            console.log("error occurred");
        });
}

async function closeAddUnitModal() {
    unitStore.resetCurrentUnitData();
    emit("close");
}

onMounted(() => {
    unitStore.resetCurrentUnitData();
    unitStore.fetchBaseUnits();
});
</script>

<template>
    <div class="modal fade show d-block">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Unit</h5>
                    <button type="button" class="close">
                        <CrossSvgIcon @click="closeAddUnitModal" />
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-item">
                        <label class="my-2">Unit Name</label>
                        <p
                            class="text-danger"
                            v-if="unitStore.add_unit_errors.name"
                        >
                            {{ unitStore.add_unit_errors.name }}
                        </p>
                        <input
                            type="text"
                            class="form-control"
                            v-model="unit_data.name"
                        />
                    </div>

                    <div class="form-item">
                        <label class="my-2">Unit Short Name</label>
                        <p
                            class="text-danger"
                            v-if="unitStore.add_unit_errors.short_name"
                        >
                            {{ unitStore.add_unit_errors.short_name }}
                        </p>
                        <input
                            type="text"
                            class="form-control"
                            v-model="unit_data.short_name"
                        />
                    </div>

                    <div class="form-item">
                        <label class="my-2">Base Unit</label>
                        <p
                            class="text-danger"
                            v-if="unitStore.add_unit_errors.base_unit_id"
                        >
                            {{ unitStore.add_unit_errors.base_unit_id }}
                        </p>
                        <select
                            class="form-select form-select-sm text-capitalize"
                            v-model="unit_data.base_unit_id"
                        >
                            <option value="">Select Base Unit</option>
                            <option
                                :value="base_unit.id"
                                v-for="base_unit in unitStore.base_units"
                            >
                                {{ base_unit.name }}
                            </option>
                        </select>
                    </div>

                    <div class="form-item" v-if="unit_data.base_unit_id">
                        <label class="my-2">Operator</label>
                        <p
                            class="text-danger"
                            v-if="unitStore.add_unit_errors.operator"
                        >
                            {{ unitStore.add_unit_errors.operator }}
                        </p>
                        <select
                            class="form-select form-select-sm text-capitalize"
                            v-model="unit_data.operator"
                        >
                            <option value="divide">divide (/)</option>
                            <option value="multiply">multuply (*)</option>
                        </select>
                    </div>

                    <div class="form-item" v-if="unit_data.base_unit_id">
                        <label class="my-2">Operation Value</label>
                        <p
                            class="text-danger"
                            v-if="unitStore.add_unit_errors.operation_value"
                        >
                            {{ unitStore.add_unit_errors.operation_value }}
                        </p>
                        <input
                            type="number"
                            class="form-control"
                            v-model="unit_data.operation_value"
                        />
                    </div>
                </div>

                <div class="modal-footer">
                    <button
                        class="btn btn-danger btn-sm"
                        @click="closeAddUnitModal"
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
