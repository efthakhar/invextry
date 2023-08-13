<script setup>
import { computed, onMounted, ref } from "vue";
import { useAuthStore } from "../../stores/authStore";
import Loader from "../../components/shared/loader/Loader.vue";
import Pagination from "../../components/shared/pagination/Pagination.vue";
import { useConfirmStore } from "../../components/shared/confirm-alert/confirmStore.js";
import { useUnitStore } from "./unitStore";
import BinSvgIcon from "../../assets/icons/bin-svg-icon.vue";
import EditSvgIcon from "../../assets/icons/edit-svg-icon.vue";
import ViewSvgIcon from "../../assets/icons/view-svg-icon.vue";
import AddNewButton from "../../components/buttons/AddNewButton.vue";
import FilterButton from "../../components/buttons/FilterButton.vue";
import BulkDeleteButton from "../../components/buttons/BulkDeleteButton.vue";
import AddUnit from "./AddUnit.vue";
import EditUnit from "./EditUnit.vue";
import ViewUnit from "./ViewUnit.vue";

const loading = ref(false);
const filterTab = ref(true);
const showAddUnit = ref(false);
const showEditUnit = ref(false);
const showViewUnit = ref(false);

const unitStore = useUnitStore();
const confirmStore = useConfirmStore();
const authStore = useAuthStore();
const units = computed(() => unitStore.units);
const q_name = ref("");
const selected_units = ref([]);
const all_selectd = ref(false);

function select_all() {
    if (all_selectd.value == false) {
        selected_units.value = [];
        unitStore.units.forEach((element) => {
            selected_units.value.push(element.id);
        });
        all_selectd.value = true;
    } else {
        all_selectd.value = false;
        selected_units.value = [];
    }
}

async function deleteData(id) {
    await confirmStore
        .show_box({ message: "Do you want to delete selected unit?" })
        .then(async () => {
            if (confirmStore.do_action == true) {
                await unitStore.deleteUnit(id);
                if (Array.isArray(id)) {
                    all_selectd.value = false;
                    selected_units.value = [];
                }
            }
        });
}

function openEditUnitModal(id) {
    unitStore.edit_unit_id = id;
    showEditUnit.value = true;
}

function openViewUnitModal(id) {
    unitStore.view_unit_id = id;
    showViewUnit.value = true;
}

async function fetchData(
    page = unitStore.current_page,
    per_page = unitStore.per_page,
    q_name = unitStore.q_name
) {
    loading.value = true;

    all_selectd.value = false;
    selected_units.value = [];

    try {
        unitStore
            .fetchUnits(page, per_page, q_name)
            .then((response) => {
                loading.value = false;
            });
    } catch (error) {
        // console.log(error);
        loading.value = false;
    }
}

onMounted(() => {
    fetchData(1);
});
</script>

<template>
    <div v-if="authStore.userCan('view_unit')">
        <div class="page-top-box mb-2 d-flex flex-wrap">
            <h3 class="h3">Units</h3>
            <div class="page-heading-actions ms-auto">
                <BulkDeleteButton
                    v-if="
                        selected_units.length > 0 &&
                        authStore.userCan('delete_unit')
                    "
                    @click="deleteData(selected_units)"
                />
                <AddNewButton
                    v-if="authStore.userCan('create_unit')"
                    @click="showAddUnit = true"
                />
                <FilterButton @click="filterTab = !filterTab" />
            </div>
        </div>
        <div class="p-1 my-2" v-if="filterTab">
            <div class="row">
                <div class="col-md-3 col-sm-6 my-1">
                    <div class="input-group">
                        <input
                            type="text"
                            class="form-control"
                            placeholder="type name.."
                            v-model="q_name"
                            @keyup="
                                fetchData(1, unitStore.per_page, q_name)
                            "
                        />
                        <label class="input-group-text">sarch</label>
                    </div>
                </div>
            </div>
        </div>

        <Loader v-if="loading" />
        <div
            class="table-responsive bg-white shadow-sm"
            v-if="loading == false"
        >
            <table class="table mb-0 table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>
                            <input
                                type="checkbox"
                                class="form-check-input"
                                @click="select_all"
                                v-model="all_selectd"
                            />
                        </th>
                        <th>ID</th>
                        <th>Unit Name</th>
                        <th>Short Name</th>
                        <th class="table-action-col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="unit in units" :key="unit.id">
                        <td>
                            <input
                                type="checkbox"
                                class="form-check-input"
                                v-model="selected_units"
                                :value="unit.id"
                            />
                        </td>
                        <td>{{ unit.id }}</td>
                        <td>{{ unit.name }}</td>
                        <td>{{ unit.short_name }}</td>
                        <td class="table-action-btns">
                            <ViewSvgIcon
                                color="#00CFDD"
                                @click="openViewUnitModal(unit.id)"
                            />
                            <EditSvgIcon
                                v-if="authStore.userCan('update_unit')"
                                color="#739EF1"
                                @click="openEditUnitModal(unit.id)"
                            />
                            <BinSvgIcon
                                v-if="authStore.userCan('delete_unit')"
                                color="#FF7474"
                                @click="deleteData(unit.id)"
                            />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <Pagination
            v-if="loading == false && units.length > 0"
            :total_pages="unitStore.total_pages"
            :current_page="unitStore.current_page"
            :per_page="unitStore.per_page"
            @pageChange="
                (currentPage) => fetchData(currentPage, unitStore.per_page)
            "
            @perPageChange="(perpage) => fetchData(1, perpage)"
        />
        <div class="modals-container">
            <AddUnit
                v-if="showAddUnit"
                @close="showAddUnit = false"
                @refreshData="fetchData(1)"
            />
            <EditUnit
                v-if="showEditUnit"
                :unit_id="unitStore.edit_unit_id"
                @close="showEditUnit = false"
                @refreshData="fetchData(unitStore.current_page)"
            />
            <ViewUnit
                v-if="showViewUnit"
                :unit_id="unitStore.view_unit_id"
                @close="showViewUnit = false"
            />
        </div>
    </div>
</template>
