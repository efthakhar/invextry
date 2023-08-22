<script setup>
import {  onMounted, reactive, ref } from "vue";
import axios from "axios";
import { useAuthStore } from "../../stores/authStore";
import Loader from "../../components/shared/loader/Loader.vue";
import Pagination from "../../components/shared/pagination/Pagination.vue";
import { useConfirmStore } from "../../components/shared/confirm-alert/confirmStore.js";
import BinSvgIcon from "../../assets/icons/bin-svg-icon.vue";
import EditSvgIcon from "../../assets/icons/edit-svg-icon.vue";
import ViewSvgIcon from "../../assets/icons/view-svg-icon.vue";
import WalletSvgIcon from "../../assets/icons/wallet-svg-icon.vue";
import AddNewButton from "../../components/buttons/AddNewButton.vue";
import FilterButton from "../../components/buttons/FilterButton.vue";
import BulkDeleteButton from "../../components/buttons/BulkDeleteButton.vue";
import AddPayment from "../payment/AddPayment.vue";

const loading = ref(false);
const filterTab = ref(true);

const confirmStore = useConfirmStore();
const authStore = useAuthStore();
const sales = ref([]);
const search = ref("");
const per_page = ref(10);
const total_pages = ref(1);
const current_page = ref(1);
const selected_sales = ref([]);
const all_selectd = ref(false);


async function fetchData(
    page = current_page.value,
    perPage = per_page.value,
    search_string = search.value
) {
    loading.value = true;
    await axios
        .get(
            `/api/sales?search=${search_string}&per_page=${perPage}&page=${page}`
        )
        .then((response) => {
            sales.value = response.data.data;
            loading.value = false;
            total_pages.value = response.data.meta.last_page;
            per_page.value = perPage;
            current_page.value = page;
            //search.value = search_string;
        })
        .catch((errors) => {
            loading.value = false;
            console.log(errors);
        });
    all_selectd.value = false;
    selected_sales.value = [];
}

let showPaymentModal = ref(false)

let paymentInfo = reactive({
    invoice_id : '',
    due_amount: ''
})

function openPaymentModal(invoice_id,due_amount) {
    showPaymentModal.value = true;
    paymentInfo.invoice_id = invoice_id;
    paymentInfo.due_amount = due_amount;
}

onMounted(() => {
    fetchData(1);
});
</script>

<template>
    <div v-if="authStore.userCan('view_sale')">
        <div class="page-top-box mb-2 d-flex flex-wrap">
            <h3 class="h3">Sales</h3>
            <div class="page-heading-actions ms-auto">
                <BulkDeleteButton
                    v-if="
                        selected_sales.length > 0 &&
                        authStore.userCan('delete_sale')
                    "
                    @click="deleteData(selected_sales)"
                />
                <AddNewButton @click="$router.push({ name: 'new_sale' })" />
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
                            placeholder="search by reference no.."
                            v-model="search"
                            @keyup="fetchData(1, per_page, search)"
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
                        <!-- <th>
                            <input
                                type="checkbox"
                                class="form-check-input"
                                @click="select_all"
                                v-model="all_selectd"
                            />
                        </th> -->
                        <th class="min100">Date</th>
                        <th class="min100">Reference</th>
                        <th class="min100">Customer</th>
                        <th class="min100">Warehouse</th>
                        <th class="min100">Total</th>
                        <th class="min100">Paid</th>
                        <th class="min100">Due</th>
                        <th class="">Sale Status</th>
                        <th class="">Payment Status</th>
                        <th class="table-action-col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="sale in sales" :key="sale.id">
                        <!-- <td>
                            <input
                                type="checkbox"
                                class="form-check-input"
                                @click="select_all"
                                v-model="all_selectd"
                            />
                        </td> -->
                        <td>{{ sale.invoice_date }}</td>
                        <td>{{ sale.invoice_ref }}</td>
                        <td>{{ sale.supplier }}</td>
                        <td>{{ sale.warehouse }}</td>
                        <td>{{ sale.total_amount }}</td>
                        <td>{{ sale.paid_amount }}</td>
                        <td>{{ sale.due_amount }}</td>
                        <td>
                            <span
                                class="badge-sqaure text-capitalize"
                                :class="[
                                    sale.invoice_status == 'received'
                                        ? 'btn-outline-success'
                                        : '',
                                    sale.invoice_status == 'ordered'
                                        ? 'btn-outline-primary'
                                        : '',
                                    sale.invoice_status == 'pending'
                                        ? 'btn-outline-warning'
                                        : '',
                                ]"
                            >
                                {{ sale.invoice_status }}
                            </span>
                        </td>
                        <td>
                            <span
                                class="badge py-1 px-2 text-capitalize"
                                :class="[
                                    sale.payment_status == 'paid'
                                        ? 'bg-success'
                                        : '',
                                    sale.payment_status == 'unpaid'
                                        ? 'bg-danger'
                                        : '',
                                    sale.payment_status == 'partial'
                                        ? 'bg-warning'
                                        : '',
                                ]"
                            >
                                {{ sale.payment_status }}
                            </span>
                        </td>
                        <td class="table-action-btns sm">
                            <!-- <ViewSvgIcon
                                color="#00CFDD"
                                @click="openViewTaxModal(sale.id)"
                            />
                            <EditSvgIcon
                                v-if="authStore.userCan('update_sale')"
                                color="#739EF1"
                                @click="openEditTaxModal(sale.id)"
                            />
                            <BinSvgIcon
                                v-if="authStore.userCan('delete_sale')"
                                color="#FF7474"
                                @click="deleteData(sale.id)"
                            /> -->
                            <WalletSvgIcon
                                v-if="authStore.userCan('create_payment')"
                                @click="openPaymentModal(sale.id,sale.due_amount)"
                            />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <Pagination
            v-if="loading == false && sales.length > 0"
            :total_pages="total_pages"
            :current_page="current_page"
            :per_page="per_page"
            @pageChange="(currentPage) => fetchData(currentPage, per_page)"
            @perPageChange="(perpage) => fetchData(1, perpage)"
        />
        <div class="modals-container">
            <AddPayment
                v-if="showPaymentModal"
                :invoice_id="paymentInfo.invoice_id"
                :due_amount="paymentInfo.due_amount"
                @close="showPaymentModal = false"
                @refreshData="fetchData(current_page, per_page)"
            />
        </div>
    </div>
</template>
