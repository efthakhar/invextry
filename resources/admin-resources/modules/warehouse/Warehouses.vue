<script setup>
import { computed, onMounted, ref } from "vue";
import { useAuthStore } from "../../stores/authStore";
import Loader from "../../components/shared/loader/Loader.vue";
import Pagination from "../../components/shared/pagination/Pagination.vue";
import { useConfirmStore } from "../../components/shared/confirm-alert/confirmStore.js";
import { useWarehouseStore } from "./warehouseStore";
import BinSvgIcon from "../../assets/icons/bin-svg-icon.vue";
import EditSvgIcon from "../../assets/icons/edit-svg-icon.vue";
import ViewSvgIcon from "../../assets/icons/view-svg-icon.vue";
import AddNewButton from "../../components/buttons/AddNewButton.vue";
import FilterButton from "../../components/buttons/FilterButton.vue";
import BulkDeleteButton from "../../components/buttons/BulkDeleteButton.vue";
import AddWarehouse from "./AddWarehouse.vue";
import EditWarehouse from "./EditWarehouse.vue";
import ViewWarehouse from "./ViewWarehouse.vue";

const loading = ref(false);
const filterTab = ref(true);
const showAddWarehouse = ref(false);
const showEditWarehouse = ref(false);
const showViewWarehouse = ref(false);

const warehouseStore = useWarehouseStore();
const confirmStore = useConfirmStore();
const authStore = useAuthStore();
const warehouses = computed(() => warehouseStore.warehouses);
const q_name = ref("");
const selected_warehouses = ref([]);
const all_selectd = ref(false);

function select_all() {
    if (all_selectd.value == false) {
        selected_warehouses.value = [];
        warehouseStore.warehouses.forEach((element) => {
            selected_warehouses.value.push(element.id);
        });
        all_selectd.value = true;
    } else {
        all_selectd.value = false;
        selected_warehouses.value = [];
    }
}

async function deleteData(id) {
    confirmStore
        .show_box({ message: "Do you want to delete selected warehouse?" })
        .then(async () => {
            if (confirmStore.do_action == true) {
                warehouseStore.deleteWarehouse(id).then(() => {
                    warehouseStore.fetchWarehouses(
                        warehouseStore.current_page,
                        warehouseStore.per_page,
                        warehouseStore.q_name
                    );

                    if (Array.isArray(id)) {
                        all_selectd.value = false;
                        selected_warehouses.value = [];
                    }
                });
            }
        });
}

function openEditWarehouseModal(id) {
    warehouseStore.edit_warehouse_id = id;
    showEditWarehouse.value = true;
}

function openViewWarehouseModal(id) {
    warehouseStore.view_warehouse_id = id;
    showViewWarehouse.value = true;
}

async function fetchData(
    page = warehouseStore.current_page,
    per_page = warehouseStore.per_page,
    q_name = warehouseStore.q_name
) {
    loading.value = true;

    all_selectd.value = false;
    selected_warehouses.value = [];

    try {
        warehouseStore
            .fetchWarehouses(page, per_page, q_name)
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
    <div v-if="authStore.userCan('view_warehouse')">
        <div class="page-top-box mb-2 d-flex flex-wrap">
            <h3 class="h3">Warehouses</h3>
            <div class="page-heading-actions ms-auto">
                <BulkDeleteButton
                    v-if="
                        selected_warehouses.length > 0 &&
                        authStore.userCan('delete_warehouse')
                    "
                    @click="deleteData(selected_warehouses)"
                />
                <AddNewButton
                    v-if="authStore.userCan('create_warehouse')"
                    @click="showAddWarehouse = true"
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
                                fetchData(1, warehouseStore.per_page, q_name)
                            "
                        />
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
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th class="table-action-col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="warehouse in warehouses" :key="warehouse.id">
                        <td>
                            <input
                                type="checkbox"
                                class="form-check-input"
                                v-model="selected_warehouses"
                                :value="warehouse.id"
                            />
                        </td>
                        <td>{{ warehouse.name }}</td>
                        <td>{{ warehouse.email }}</td>
                        <td>{{ warehouse.phone }}</td>
                        <td>{{ warehouse.address }}</td>
                        <td class="table-action-btns">
                            <ViewSvgIcon
                                color="#00CFDD"
                                @click="openViewWarehouseModal(warehouse.id)"
                            />
                            <EditSvgIcon
                                v-if="authStore.userCan('update_warehouse')"
                                color="#739EF1"
                                @click="openEditWarehouseModal(warehouse.id)"
                            />
                            <BinSvgIcon
                                v-if="authStore.userCan('delete_warehouse')"
                                color="#FF7474"
                                @click="deleteData(warehouse.id)"
                            />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <Pagination
            v-if="loading == false && warehouses.length > 0"
            :total_pages="warehouseStore.total_pages"
            :current_page="warehouseStore.current_page"
            :per_page="warehouseStore.per_page"
            @pageChange="
                (currentPage) => fetchData(currentPage, warehouseStore.per_page)
            "
            @perPageChange="(perpage) => fetchData(1, perpage)"
        />
        <div class="modals-container">
            <AddWarehouse
                v-if="showAddWarehouse"
                @close="showAddWarehouse = false"
                @refreshData="fetchData(1)"
            />
            <EditWarehouse
                v-if="showEditWarehouse"
                :warehouse_id="warehouseStore.edit_warehouse_id"
                @close="showEditWarehouse = false"
                @refreshData="fetchData(warehouseStore.current_page)"
            />
            <ViewWarehouse
                v-if="showViewWarehouse"
                :warehouse_id="warehouseStore.view_warehouse_id"
                @close="showViewWarehouse = false"
            />
        </div>
    </div>
</template>
