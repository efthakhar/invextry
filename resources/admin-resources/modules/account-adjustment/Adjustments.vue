<script setup>
import { computed, onMounted, ref } from "vue";
import { useAuthStore } from "../../stores/authStore";
import Loader from "../../components/shared/loader/Loader.vue";
import Pagination from "../../components/shared/pagination/Pagination.vue";
import { useConfirmStore } from "../../components/shared/confirm-alert/confirmStore.js";
import { useAdjustmentStore } from "./adjustmentStore";
// import BinSvgIcon from "../../assets/icons/bin-svg-icon.vue";
// import EditSvgIcon from "../../assets/icons/edit-svg-icon.vue";
// import ViewSvgIcon from "../../assets/icons/view-svg-icon.vue";
import AddNewButton from "../../components/buttons/AddNewButton.vue";
import FilterButton from "../../components/buttons/FilterButton.vue";
// import BulkDeleteButton from "../../components/buttons/BulkDeleteButton.vue";
import AddAdjustment from "./AddAdjustment.vue";
// import EditAdjustment from "./EditAdjustment.vue";
// import ViewAdjustment from "./ViewAdjustment.vue";

const loading = ref(false);
const filterTab = ref(true);
const showAddAdjustment = ref(false);
// const showEditAdjustment = ref(false);
// const showViewAdjustment = ref(false);

const adjustmentStore = useAdjustmentStore();
const confirmStore = useConfirmStore();
const authStore = useAuthStore();
const adjustments = computed(() => adjustmentStore.adjustments);
const search = ref("");
const selected_adjustments = ref([]);
const all_selectd = ref(false);

// function select_all() {
//     if (all_selectd.value == false) {
//         selected_adjustments.value = [];
//         adjustmentStore.adjustments.forEach((element) => {
//             selected_adjustments.value.push(element.id);
//         });
//         all_selectd.value = true;
//     } else {
//         all_selectd.value = false;
//         selected_adjustments.value = [];
//     }
// }

async function fetchData(
    page = adjustmentStore.current_page,
    per_page = adjustmentStore.per_page,
    search = adjustmentStore.search
) {
    loading.value = true;

    all_selectd.value = false;
    selected_adjustments.value = [];

    try {
        adjustmentStore
            .fetchAdjustments(page, per_page, search)
            .then((response) => {
                loading.value = false;
            });
    } catch (error) {
        loading.value = false;
    }
}

// async function deleteData(id) {
//     confirmStore
//         .show_box({ message: "Do you want to delete selected Adjustment?" })
//         .then(async () => {
//             if (confirmStore.do_action == true) {
//                 adjustmentStore.deleteAdjustment(id).then(() => {
//                     adjustmentStore.fetchAdjustments(
//                         adjustmentStore.current_page,
//                         adjustmentStore.per_page,
//                         adjustmentStore.search,
//                         adjustmentStore.q_status
//                     );

//                     if (Array.isArray(id)) {
//                         all_selectd.value = false;
//                         selected_adjustments.value = [];
//                     }
//                 });
//             }
//         });
// }

// function openEditAdjustmentModal(id) {
//     adjustmentStore.edit_adjustment_id = id;
//     showEditAdjustment.value = true;
// }

// function openViewAdjustmentModal(id) {
//     adjustmentStore.view_adjustment_id = id;
//     showViewAdjustment.value = true;
// }

onMounted(() => {
    fetchData(1);
});
</script>

<template>
    <div v-if="authStore.userCan('view_account_adjustment')">
        <div class="page-top-box mb-2 d-flex flex-wrap">
            <h3 class="h3">Balance Adjustments</h3>
            <div class="page-heading-actions ms-auto">
                <!-- <BulkDeleteButton
                    v-if="
                        selected_adjustments.length > 0 &&
                        authStore.userCan('delete_account_adjustment')
                    "
                    @click="deleteData(selected_adjustments)"
                /> -->
                <AddNewButton
                    v-if="authStore.userCan('create_account_adjustment')"
                    @click="showAddAdjustment = true"
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
                            v-model="search"
                            @keyup="
                                fetchData(1, adjustmentStore.per_page, search)
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
                        <!-- <th>
                            <input
                                type="checkbox"
                                class="form-check-input"
                                @click="select_all"
                                v-model="all_selectd"
                            />
                        </th> -->
                        <th>Bank Name</th>
                        <th>Branch</th>
                        <th>Adjustment Number</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <!-- <th class="table-action-col">Action</th> -->
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="adjustment in adjustments" :key="adjustment.id">
                        <!-- <td>
                            <input
                                type="checkbox"
                                class="form-check-input"
                                v-model="selected_adjustments"
                                :value="adjustment.id"
                            />
                        </td> -->
                        <td>{{ adjustment.account.bank_name }}</td>
                        <td>{{ adjustment.account.branch_name }}</td>
                        <td>{{ adjustment.account.account_number }}</td>
                        <td>
                            <span
                                class="badge-sqaure text-uppercase"
                                :class="[
                                    adjustment.type == 'add'
                                        ? 'btn-outline-success'
                                        : '',
                                    adjustment.type == 'subtract'
                                        ? 'btn-outline-danger'
                                        : '',
                                ]"
                            >
                                {{ adjustment.type }}
                            </span>
                        </td>
                        <td>{{ adjustment.amount }}</td>

                        <!-- <td class="table-action-btns">
                            <ViewSvgIcon
                                color="#00CFDD"
                                @click="openViewAdjustmentModal(adjustment.id)"
                            />
                            <EditSvgIcon
                                v-if="authStore.userCan('update_adjustment')"
                                color="#739EF1"
                                @click="openEditAdjustmentModal(adjustment.id)"
                            />
                            <BinSvgIcon
                                v-if="authStore.userCan('delete_adjustment')"
                                color="#FF7474"
                                @click="deleteData(adjustment.id)"
                            />
                        </td> -->
                    </tr>
                </tbody>
            </table>
        </div>
        <Pagination
            v-if="loading == false && adjustments.length > 0"
            :total_pages="adjustmentStore.total_pages"
            :current_page="adjustmentStore.current_page"
            :per_page="adjustmentStore.per_page"
            @pageChange="
                (currentPage) =>
                    fetchData(currentPage, adjustmentStore.per_page)
            "
            @perPageChange="(perpage) => fetchData(1, perpage)"
        />
        <div class="modals-container">
            <AddAdjustment
                v-if="showAddAdjustment"
                @close="showAddAdjustment = false"
                @refreshData="fetchData(1)"
            />
            <!-- <EditAdjustment
                v-if="showEditAdjustment"
                :adjustment_id="adjustmentStore.edit_adjustment_id"
                @close="showEditAdjustment = false"
                @refreshData="fetchData(adjustmentStore.current_page)"
            />
            <ViewAdjustment
                v-if="showViewAdjustment"
                :adjustment_id="adjustmentStore.view_adjustment_id"
                @close="showViewAdjustment = false"
            /> -->
        </div>
    </div>
</template>
