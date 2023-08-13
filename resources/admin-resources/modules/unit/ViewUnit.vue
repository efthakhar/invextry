<script setup>
import { ref, computed, onMounted } from "vue";
import CrossSvgIcon from "../../assets/icons/cross-svg-icon.vue";
import Loader from "../../components/shared/loader/Loader.vue";
import { useUnitStore } from "./unitStore";

const props = defineProps(["unit_id"]);
const emit = defineEmits(["close"]);

const loading = ref(false);
const unitStore = useUnitStore();
const unit_data = computed(() => unitStore.current_unit_item);

async function fetchData(id) {
    loading.value = true;
    await unitStore.fetchUnit(id);
    loading.value = false;
}

async function closeViewUnitModal() {
    unitStore.resetCurrentUnitData();
    emit("close");
}

onMounted(() => {
    fetchData(props.unit_id);
    unitStore.fetchBaseUnits();
});
</script>

<template>
    <div class="modal fade show d-block">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Unit Details</h5>
                    <button type="button" class="close">
                        <CrossSvgIcon @click="closeViewUnitModal" />
                    </button>
                </div>
                <div class="modal-body pb-5">
                    <Loader v-if="loading" />
                    <div class="form-items" v-if="loading == false">
                        <label class="my-2">Unit Name</label>
                        <input
                            disabled
                            type="text"
                            class="form-control"
                            v-model="unit_data.name"
                        />
                        <label class="my-2">Unit Short Name</label>
                        <input
                            disabled
                            type="text"
                            class="form-control"
                            v-model="unit_data.short_name"
                        />

                        <div class="form-item">
                            <label class="my-2">Base Unit</label>
                            <select
                                disabled
                                class="form-select form-select-sm text-capitalize"
                                v-model="unit_data.base_unit_id"
                            >
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
                            <select
                                disabled
                                class="form-select form-select-sm text-capitalize"
                                v-model="unit_data.operator"
                            >
                                <option value="divide">divide (/)</option>
                                <option value="multiply">multuply (*)</option>
                            </select>
                        </div>

                        <div class="form-item" v-if="unit_data.base_unit_id">
                            <label class="my-2">Operation Value</label>
                            <input
                                disabled
                                type="number"
                                class="form-control"
                                v-model="unit_data.operation_value"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
