<script setup>
import { computed, onMounted, ref } from "vue";
import { useAuthStore } from "../../stores/authStore";
import Loader from "../../components/shared/loader/Loader.vue";
import Pagination from "../../components/shared/pagination/Pagination.vue";
import { useConfirmStore } from "../../components/shared/confirm-alert/confirmStore.js";
import { useAccountStore } from "./accountStore";
import BinSvgIcon from "../../assets/icons/bin-svg-icon.vue";
import EditSvgIcon from "../../assets/icons/edit-svg-icon.vue";
import ViewSvgIcon from "../../assets/icons/view-svg-icon.vue";
import AddNewButton from "../../components/buttons/AddNewButton.vue";
import FilterButton from "../../components/buttons/FilterButton.vue";
import BulkDeleteButton from "../../components/buttons/BulkDeleteButton.vue";
import AddAccount from "./AddAccount.vue";
import EditAccount from "./EditAccount.vue";
import ViewAccount from "./ViewAccount.vue";

const loading = ref(false);
const filterTab = ref(true);
const showAddAccount = ref(false);
const showEditAccount = ref(false);
const showViewAccount = ref(false);

const accountStore = useAccountStore();
const confirmStore = useConfirmStore();
const authStore = useAuthStore();
const accounts = computed(() => accountStore.accounts);
const q_name = ref("");
const selected_accounts = ref([]);
const all_selectd = ref(false);

function select_all() {
    if (all_selectd.value == false) {
        selected_accounts.value = [];
        accountStore.accounts.forEach((element) => {
            selected_accounts.value.push(element.id);
        });
        all_selectd.value = true;
    } else {
        all_selectd.value = false;
        selected_accounts.value = [];
    }
}

async function fetchData(
    page = accountStore.current_page,
    per_page = accountStore.per_page,
    q_name = accountStore.q_name,
    q_status = accountStore.q_status
) {
    loading.value = true;

    all_selectd.value = false;
    selected_accounts.value = [];

    try {
        accountStore
            .fetchAccounts(page, per_page, q_name, q_status)
            .then((response) => {
                loading.value = false;
            });
    } catch (error) {
        loading.value = false;
    }
}

async function deleteData(id) {
    confirmStore
        .show_box({ message: "Do you want to delete selected Account?" })
        .then(async () => {
            if (confirmStore.do_action == true) {
                accountStore.deleteAccount(id).then(() => {
                    accountStore.fetchAccounts(
                        accountStore.current_page,
                        accountStore.per_page,
                        accountStore.q_name,
                        accountStore.q_status
                    );

                    if (Array.isArray(id)) {
                        all_selectd.value = false;
                        selected_accounts.value = [];
                    }
                });
            }
        });
}

function openEditAccountModal(id) {
    accountStore.edit_account_id = id;
    showEditAccount.value = true;
}

function openViewAccountModal(id) {
    accountStore.view_account_id = id;
    showViewAccount.value = true;
}

onMounted(() => {
    fetchData(1);
});
</script>

<template>
    <div v-if="authStore.userCan('view_account')">
        <div class="page-top-box mb-2 d-flex flex-wrap">
            <h3 class="h3">Account List</h3>
            <div class="page-heading-actions ms-auto">
                <BulkDeleteButton
                    v-if="
                        selected_accounts.length > 0 &&
                        authStore.userCan('delete_account')
                    "
                    @click="deleteData(selected_accounts)"
                />
                <AddNewButton
                    v-if="authStore.userCan('create_account')"
                    @click="showAddAccount = true"
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
                            @keyup="fetchData(1, accountStore.per_page, q_name)"
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
                        <th>Number</th>
                        <th>Balance</th>
                        <th>Status</th>
                        <th class="table-action-col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="account in accounts" :key="account.id">
                        <td>
                            <input
                                type="checkbox"
                                class="form-check-input"
                                v-model="selected_accounts"
                                :value="account.id"
                            />
                        </td>
                        <td>{{ account.name }}</td>
                        <td>{{ account.account_number }}</td>
                        <td>{{ account.balance }}</td>
                        <td>
                            <span
                                class="badge-sqaure text-uppercase"
                                :class="[
                                    account.status == 'active'
                                        ? 'btn-outline-success'
                                        : '',
                                    account.status == 'disabled'
                                        ? 'btn-outline-secondary'
                                        : '',
                                ]"
                            >
                                {{ account.status }}
                            </span>
                        </td>
                        <td class="table-action-btns">
                            <ViewSvgIcon
                                color="#00CFDD"
                                @click="openViewAccountModal(account.id)"
                            />
                            <EditSvgIcon
                                v-if="authStore.userCan('update_account')"
                                color="#739EF1"
                                @click="openEditAccountModal(account.id)"
                            />
                            <BinSvgIcon
                                v-if="authStore.userCan('delete_account')"
                                color="#FF7474"
                                @click="deleteData(account.id)"
                            />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <Pagination
            v-if="loading == false && accounts.length > 0"
            :total_pages="accountStore.total_pages"
            :current_page="accountStore.current_page"
            :per_page="accountStore.per_page"
            @pageChange="
                (currentPage) => fetchData(currentPage, accountStore.per_page)
            "
            @perPageChange="(perpage) => fetchData(1, perpage)"
        />
        <div class="modals-container">
            <AddAccount
                v-if="showAddAccount"
                @close="showAddAccount = false"
                @refreshData="fetchData(1)"
            />
            <EditAccount
                v-if="showEditAccount"
                :account_id="accountStore.edit_account_id"
                @close="showEditAccount = false"
                @refreshData="fetchData(accountStore.current_page)"
            />
            <ViewAccount
                v-if="showViewAccount"
                :account_id="accountStore.view_account_id"
                @close="showViewAccount = false"
            />
        </div>
    </div>
</template>
