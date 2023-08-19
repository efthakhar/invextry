<script setup>
import { computed, onMounted, ref } from "vue";
import { useAuthStore } from "../../stores/authStore";
import Loader from "../../components/shared/loader/Loader.vue";
import Pagination from "../../components/shared/pagination/Pagination.vue";
import { useConfirmStore } from "../../components/shared/confirm-alert/confirmStore.js";
import BinSvgIcon from "../../assets/icons/bin-svg-icon.vue";
import EditSvgIcon from "../../assets/icons/edit-svg-icon.vue";
import ViewSvgIcon from "../../assets/icons/view-svg-icon.vue";
import AddNewButton from "../../components/buttons/AddNewButton.vue";
import FilterButton from "../../components/buttons/FilterButton.vue";
import BulkDeleteButton from "../../components/buttons/BulkDeleteButton.vue";

const loading = ref(false);
const filterTab = ref(true);

const confirmStore = useConfirmStore();
const authStore = useAuthStore();
const purchases = ref([]);
const q_name = ref("");
const per_page = ref(10);
const page = ref(1);
const selected_purchases = ref([]);
const all_selectd = ref(false);

function select_all() {
    if (all_selectd.value == false) {
        selected_purchases.value = [];
        purchaseStore.purchases.forEach((element) => {
            selected_purchases.value.push(element.id);
        });
        all_selectd.value = true;
    } else {
        all_selectd.value = false;
        selected_purchases.value = [];
    }
}

async function deleteData(id) {
    confirmStore
        .show_box({ message: "Do you want to delete selected purchase?" })
        .then(async () => {
            if (confirmStore.do_action == true) {
                purchaseStore.deleteTax(id).then(() => {
                    purchaseStore.fetchPurchases(
                        purchaseStore.current_page,
                        purchaseStore.per_page,
                        purchaseStore.q_name
                    );

                    if (Array.isArray(id)) {
                        all_selectd.value = false;
                        selected_purchases.value = [];
                    }
                });
            }
        });
}

async function fetchData(
    page = page.value,
    per_page = per_page.value,
    q_name = q_name.value
) {
    loading.value = true;
    await axios
        .get(`/api/purchases`)
        .then((response) => {
            purchases.value = response.data;
            loading.value = false;
        })
        .catch((errors) => {
            loading.value = false;
            console.log(errors);
        });
    all_selectd.value = false;
    selected_purchases.value = [];
}

onMounted(() => {
    fetchData(1);
});
</script>

<template>
    <div v-if="authStore.userCan('view_purchase')">
        <div class="page-top-box mb-2 d-flex flex-wrap">
            <h3 class="h3">Purchases</h3>
            <div class="page-heading-actions ms-auto">
                <BulkDeleteButton
                    v-if="
                        selected_purchases.length > 0 &&
                        authStore.userCan('delete_purchase')
                    "
                    @click="deleteData(selected_purchases)"
                />
                <AddNewButton
                    v-if="authStore.userCan('create_purchase')"
                    @click="showAddTax = true"
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
                            @keyup="fetchData(1, per_page, q_name)"
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
                        <th>Date</th>
                        <th>Reference</th>
                        <th>Supplier</th>
                        <th>Warehouse</th>
                        <th>Grand Total</th>
                        <th>Status</th>
                        <th>Payment Status</th>
                        <th class="table-action-col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="purchase in purchases" :key="purchase.id">
                        <td>
                            <input
                                type="checkbox"
                                class="form-check-input"
                                @click="select_all"
                                v-model="all_selectd"
                            />
                        </td>
                        <td>Date</td>
                        <td>Reference</td>
                        <td>Supplier</td>
                        <td>Warehouse</td>
                        <td>Grand Total</td>
                        <td>Status</td>
                        <td>Payment Status</td>
                        <td class="table-action-btns">
                            <ViewSvgIcon
                                color="#00CFDD"
                                @click="openViewTaxModal(purchase.id)"
                            />
                            <EditSvgIcon
                                v-if="authStore.userCan('update_purchase')"
                                color="#739EF1"
                                @click="openEditTaxModal(purchase.id)"
                            />
                            <BinSvgIcon
                                v-if="authStore.userCan('delete_purchase')"
                                color="#FF7474"
                                @click="deleteData(purchase.id)"
                            />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <Pagination
            v-if="loading == false && purchases.length > 0"
            :total_pages="total_pages"
            :current_page="page"
            :per_page="pper_page"
            @pageChange="(currentPage) => fetchData(currentPage, per_page)"
            @perPageChange="(perpage) => fetchData(1, perpage)"
        />
    </div>
</template>
