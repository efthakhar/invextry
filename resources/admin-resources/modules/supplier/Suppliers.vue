<script setup>
import { computed, onMounted, ref } from "vue";
import { useAuthStore } from "../../stores/authStore";
import Loader from "../../components/shared/loader/Loader.vue";
import Pagination from "../../components/shared/pagination/Pagination.vue";
import { useConfirmStore } from "../../components/shared/confirm-alert/confirmStore.js";
import { useSupplierStore } from "./supplierStore";
import BinSvgIcon from "../../assets/icons/bin-svg-icon.vue";
import EditSvgIcon from "../../assets/icons/edit-svg-icon.vue";
import ViewSvgIcon from "../../assets/icons/view-svg-icon.vue";
import AddNewButton from "../../components/buttons/AddNewButton.vue";
import FilterButton from "../../components/buttons/FilterButton.vue";
import BulkDeleteButton from "../../components/buttons/BulkDeleteButton.vue";
import AddSupplier from "./AddSupplier.vue";
import EditSupplier from "./EditSupplier.vue";
import ViewSupplier from "./ViewSupplier.vue";

const loading = ref(false);
const filterTab = ref(true);
const showAddSupplier = ref(false);
const showEditSupplier = ref(false);
const showViewSupplier = ref(false);

const supplierStore = useSupplierStore();
const confirmStore = useConfirmStore();
const authStore = useAuthStore();
const suppliers = computed(() => supplierStore.suppliers);
const q_name = ref("");
const selected_suppliers = ref([]);
const all_selectd = ref(false);

function select_all() {
    if (all_selectd.value == false) {
        selected_suppliers.value = [];
        supplierStore.suppliers.forEach((element) => {
            selected_suppliers.value.push(element.id);
        });
        all_selectd.value = true;
    } else {
        all_selectd.value = false;
        selected_suppliers.value = [];
    }
}

async function fetchData(
    page = supplierStore.current_page,
    per_page = supplierStore.per_page,
    q_name = supplierStore.q_name,
    q_status = supplierStore.q_status
) {
    loading.value = true;

    all_selectd.value = false;
    selected_suppliers.value = [];

    try {
        supplierStore
            .fetchSuppliers(page, per_page, q_name, q_status)
            .then((response) => {
                loading.value = false;
            });
    } catch (error) {
        loading.value = false;
    }
}

async function deleteData(id) {
    confirmStore
        .show_box({ message: "Do you want to delete selected Supplier?" })
        .then(async () => {
            if (confirmStore.do_action == true) {
                supplierStore.deleteSupplier(id).then(() => {
                    supplierStore.fetchSuppliers(
                        supplierStore.current_page,
                        supplierStore.per_page,
                        supplierStore.q_name,
                        supplierStore.q_status
                    );

                    if (Array.isArray(id)) {
                        all_selectd.value = false;
                        selected_suppliers.value = [];
                    }
                });
            }
        });
}

function openEditSupplierModal(id) {
    supplierStore.edit_supplier_id = id;
    showEditSupplier.value = true;
}

function openViewSupplierModal(id) {
    supplierStore.view_supplier_id = id;
    showViewSupplier.value = true;
}

onMounted(() => {
    fetchData(1);
});
</script>

<template>
    <div v-if="authStore.userCan('view_supplier')">
        <div class="page-top-box mb-2 d-flex flex-wrap">
            <h3 class="h3">Supplier List</h3>
            <div class="page-heading-actions ms-auto">
                <BulkDeleteButton
                    v-if="
                        selected_suppliers.length > 0 &&
                        authStore.userCan('delete_supplier')
                    "
                    @click="deleteData(selected_suppliers)"
                />
                <AddNewButton
                    v-if="authStore.userCan('create_supplier')"
                    @click="showAddSupplier = true"
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
                                fetchData(1, supplierStore.per_page, q_name)
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
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Purchase Due</th>
                        <th>Purchase Return Due</th>
                        <th class="table-action-col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="supplier in suppliers" :key="supplier.id">
                        <td>
                            <input
                                type="checkbox"
                                class="form-check-input"
                                v-model="selected_suppliers"
                                :value="supplier.id"
                            />
                        </td>
                        <td>{{ supplier.name }}</td>
                        <td>{{ supplier.phone }}</td>
                        <td>{{ supplier.email }}</td>
                        <td>{{ supplier.purchase_due }}</td>
                        <td>{{ supplier.purchase_return_due }}</td>
                        <td class="table-action-btns">
                            <ViewSvgIcon
                                color="#00CFDD"
                                @click="openViewSupplierModal(supplier.id)"
                            />
                            <EditSvgIcon
                                v-if="authStore.userCan('update_supplier')"
                                color="#739EF1"
                                @click="openEditSupplierModal(supplier.id)"
                            />
                            <BinSvgIcon
                                v-if="authStore.userCan('delete_supplier')"
                                color="#FF7474"
                                @click="deleteData(supplier.id)"
                            />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <Pagination
            v-if="loading == false && suppliers.length > 0"
            :total_pages="supplierStore.total_pages"
            :current_page="supplierStore.current_page"
            :per_page="supplierStore.per_page"
            @pageChange="
                (currentPage) => fetchData(currentPage, supplierStore.per_page)
            "
            @perPageChange="(perpage) => fetchData(1, perpage)"
        />
        <div class="modals-container">
            <AddSupplier
                v-if="showAddSupplier"
                @close="showAddSupplier = false"
                @refreshData="fetchData(1)"
            />
            <EditSupplier
                v-if="showEditSupplier"
                :supplier_id="supplierStore.edit_supplier_id"
                @close="showEditSupplier = false"
                @refreshData="fetchData(supplierStore.current_page)"
            />
            <ViewSupplier
                v-if="showViewSupplier"
                :supplier_id="supplierStore.view_supplier_id"
                @close="showViewSupplier = false"
            />
        </div>
    </div>
</template>
