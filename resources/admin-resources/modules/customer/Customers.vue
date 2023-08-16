<script setup>
import { computed, onMounted, ref } from "vue";
import { useAuthStore } from "../../stores/authStore";
import Loader from "../../components/shared/loader/Loader.vue";
import Pagination from "../../components/shared/pagination/Pagination.vue";
import { useConfirmStore } from "../../components/shared/confirm-alert/confirmStore.js";
import { useCustomerStore } from "./customerStore";
import BinSvgIcon from "../../assets/icons/bin-svg-icon.vue";
import EditSvgIcon from "../../assets/icons/edit-svg-icon.vue";
import ViewSvgIcon from "../../assets/icons/view-svg-icon.vue";
import AddNewButton from "../../components/buttons/AddNewButton.vue";
import FilterButton from "../../components/buttons/FilterButton.vue";
import BulkDeleteButton from "../../components/buttons/BulkDeleteButton.vue";

const loading = ref(false);
const filterTab = ref(true);

const customerStore = useCustomerStore();
const confirmStore = useConfirmStore();
const authStore = useAuthStore();
const customers = computed(() => customerStore.customers);
const q_name = ref("");
const selected_customers = ref([]);
const all_selectd = ref(false);

function select_all() {
    if (all_selectd.value == false) {
        selected_customers.value = [];
        customerStore.customers.forEach((element) => {
            selected_customers.value.push(element.id);
        });
        all_selectd.value = true;
    } else {
        all_selectd.value = false;
        selected_customers.value = [];
    }
}

async function fetchData(
    page = customerStore.current_page,
    per_page = customerStore.per_page,
    q_name = customerStore.q_name,
    q_status = customerStore.q_status
) {
    loading.value = true;

    all_selectd.value = false;
    selected_customers.value = [];

    try {
        customerStore.fetchCustomers(page, per_page, q_name, q_status).then((response) => {
            loading.value = false;
        });
    } catch (error) {
        loading.value = false;
    }
}

onMounted(() => {
    fetchData(1);
});
</script>

<template>
    <div v-if="authStore.userCan('view_customer')">
        <div class="page-top-box mb-2 d-flex flex-wrap">
            <h3 class="h3">Customer List</h3>
            <div class="page-heading-actions ms-auto">
                <BulkDeleteButton
                    v-if="
                        selected_customers.length > 0 &&
                        authStore.userCan('delete_customer')
                    "
                    @click="deleteData(selected_customers)"
                />
                <AddNewButton
                    v-if="authStore.userCan('create_customer')"
                    @click="showAddBrand = true"
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
                                fetchData(1, customerStore.per_page, q_name)
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
                        <th>Sale Due</th>
                        <th>Sale Return Due</th>
                        <th class="table-action-col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="customer in customers" :key="customer.id">
                        <td>
                            <input
                                type="checkbox"
                                class="form-check-input"
                                v-model="selected_customers"
                                :value="customer.id"
                            />
                        </td>
                        <td>{{ customer.name }}</td>
                        <td>{{ customer.phone }}</td>
                        <td>{{ customer.email }}</td>
                        <td>{{ customer.sale_due }}</td>
                        <td>{{ customer.sale_return_due }}</td>
                        <td class="table-action-btns">
                            <ViewSvgIcon
                                color="#00CFDD"
                                
                            />
                            <EditSvgIcon
                                v-if="authStore.userCan('update_customer')"
                                color="#739EF1"
                            />
                            <BinSvgIcon
                                v-if="authStore.userCan('delete_customer')"
                                color="#FF7474"
                            />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <Pagination
            v-if="loading == false && customers.length > 0"
            :total_pages="customerStore.total_pages"
            :current_page="customerStore.current_page"
            :per_page="customerStore.per_page"
            @pageChange="
                (currentPage) => fetchData(currentPage, customerStore.per_page)
            "
            @perPageChange="(perpage) => fetchData(1, perpage)"
        />
        <div class="modals-container">
           
        </div>
    </div>
</template>
