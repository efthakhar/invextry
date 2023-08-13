<script setup>
import { computed, onMounted, ref } from "vue";
import { useAuthStore } from "../../stores/authStore";
import Loader from "../../components/shared/loader/Loader.vue";
import Pagination from "../../components/shared/pagination/Pagination.vue";
import { useConfirmStore } from "../../components/shared/confirm-alert/confirmStore.js";
import { useTaxStore } from "./taxStore";
import BinSvgIcon from "../../assets/icons/bin-svg-icon.vue";
import EditSvgIcon from "../../assets/icons/edit-svg-icon.vue";
import ViewSvgIcon from "../../assets/icons/view-svg-icon.vue";
import AddNewButton from "../../components/buttons/AddNewButton.vue";
import FilterButton from "../../components/buttons/FilterButton.vue";
import BulkDeleteButton from "../../components/buttons/BulkDeleteButton.vue";
import AddTax from "./AddTax.vue";
import EditTax from "./EditTax.vue";
import ViewTax from "./ViewTax.vue";

const loading = ref(false);
const filterTab = ref(true);
const showAddTax = ref(false);
const showEditTax = ref(false);
const showViewTax = ref(false);

const taxStore = useTaxStore();
const confirmStore = useConfirmStore();
const authStore = useAuthStore();
const taxes = computed(() => taxStore.taxes);
const q_name = ref("");
const selected_taxes = ref([]);
const all_selectd = ref(false);

function select_all() {
    if (all_selectd.value == false) {
        selected_taxes.value = [];
        taxStore.taxes.forEach((element) => {
            selected_taxes.value.push(element.id);
        });
        all_selectd.value = true;
    } else {
        all_selectd.value = false;
        selected_taxes.value = [];
    }
}

async function deleteData(id) {
    confirmStore
        .show_box({ message: "Do you want to delete selected tax?" })
        .then(async () => {
            if (confirmStore.do_action == true) {
                taxStore.deleteTax(id).then(() => {
                    taxStore.fetchTaxes(
                        taxStore.current_page,
                        taxStore.per_page,
                        taxStore.q_name
                    );

                    if (Array.isArray(id)) {
                        all_selectd.value = false;
                        selected_taxes.value = [];
                    }
                });
            }
        });
}

function openEditTaxModal(id) {
    taxStore.edit_tax_id = id;
    showEditTax.value = true;
}

function openViewTaxModal(id) {
    taxStore.view_tax_id = id;
    showViewTax.value = true;
}

async function fetchData(
    page = taxStore.current_page,
    per_page = taxStore.per_page,
    q_name = taxStore.q_name
) {
    loading.value = true;

    all_selectd.value = false;
    selected_taxes.value = [];

    try {
        taxStore.fetchTaxes(page, per_page, q_name).then((response) => {
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
    <div v-if="authStore.userCan('view_tax')">
        <div class="page-top-box mb-2 d-flex flex-wrap">
            <h3 class="h3">Taxes</h3>
            <div class="page-heading-actions ms-auto">
                <BulkDeleteButton
                    v-if="
                        selected_taxes.length > 0 &&
                        authStore.userCan('delete_tax')
                    "
                    @click="deleteData(selected_taxes)"
                />
                <AddNewButton
                    v-if="authStore.userCan('create_tax')"
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
                            @keyup="fetchData(1, taxStore.per_page, q_name)"
                        />
                        <!-- <label class="input-group-text">sarch</label> -->
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
                        <th>Tax Name</th>
                        <th>Tax Rate (%)</th>
                        <th class="table-action-col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="tax in taxes" :key="tax.id">
                        <td>
                            <input
                                type="checkbox"
                                class="form-check-input"
                                v-model="selected_taxes"
                                :value="tax.id"
                            />
                        </td>
                        <td>{{ tax.name }}</td>
                        <td>{{ tax.rate }} %</td>
                        <td class="table-action-btns">
                            <ViewSvgIcon
                                color="#00CFDD"
                                @click="openViewTaxModal(tax.id)"
                            />
                            <EditSvgIcon
                                v-if="authStore.userCan('update_tax')"
                                color="#739EF1"
                                @click="openEditTaxModal(tax.id)"
                            />
                            <BinSvgIcon
                                v-if="authStore.userCan('delete_tax')"
                                color="#FF7474"
                                @click="deleteData(tax.id)"
                            />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <Pagination
            v-if="loading == false && taxes.length > 0"
            :total_pages="taxStore.total_pages"
            :current_page="taxStore.current_page"
            :per_page="taxStore.per_page"
            @pageChange="
                (currentPage) => fetchData(currentPage, taxStore.per_page)
            "
            @perPageChange="(perpage) => fetchData(1, perpage)"
        />
        <div class="modals-container">
            <AddTax
                v-if="showAddTax"
                @close="showAddTax = false"
                @refreshData="fetchData(1)"
            />
            <EditTax
                v-if="showEditTax"
                :tax_id="taxStore.edit_tax_id"
                @close="showEditTax = false"
                @refreshData="fetchData(taxStore.current_page)"
            />
            <ViewTax
                v-if="showViewTax"
                :tax_id="taxStore.view_tax_id"
                @close="showViewTax = false"
            />
        </div>
    </div>
</template>
