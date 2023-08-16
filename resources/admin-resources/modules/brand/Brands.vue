<script setup>
import { computed, onMounted, ref } from "vue";
import { useAuthStore } from "../../stores/authStore";
import Loader from "../../components/shared/loader/Loader.vue";
import Pagination from "../../components/shared/pagination/Pagination.vue";
import { useConfirmStore } from "../../components/shared/confirm-alert/confirmStore.js";
import { useBrandStore } from "./brandStore";
import BinSvgIcon from "../../assets/icons/bin-svg-icon.vue";
import EditSvgIcon from "../../assets/icons/edit-svg-icon.vue";
import ViewSvgIcon from "../../assets/icons/view-svg-icon.vue";
import AddNewButton from "../../components/buttons/AddNewButton.vue";
import FilterButton from "../../components/buttons/FilterButton.vue";
import BulkDeleteButton from "../../components/buttons/BulkDeleteButton.vue";
import AddBrand from "./AddBrand.vue";
import EditBrand from "./EditBrand.vue";
import ViewBrand from "./ViewBrand.vue";

const loading = ref(false);
const filterTab = ref(true);
const showAddBrand = ref(false);
const showEditBrand = ref(false);
const showViewBrand = ref(false);

const brandStore = useBrandStore();
const confirmStore = useConfirmStore();
const authStore = useAuthStore();
const brands = computed(() => brandStore.brands);
const q_name = ref("");
const selected_brands = ref([]);
const all_selectd = ref(false);

function select_all() {
    if (all_selectd.value == false) {
        selected_brands.value = [];
        brandStore.brands.forEach((element) => {
            selected_brands.value.push(element.id);
        });
        all_selectd.value = true;
    } else {
        all_selectd.value = false;
        selected_brands.value = [];
    }
}

async function deleteData(id) {
    confirmStore
        .show_box({ message: "Do you want to delete selected brand?" })
        .then(async () => {
            if (confirmStore.do_action == true) {
                brandStore.deleteBrand(id).then(() => {
                    brandStore.fetchBrands(
                        brandStore.current_page,
                        brandStore.per_page,
                        brandStore.q_name
                    );

                    if (Array.isArray(id)) {
                        all_selectd.value = false;
                        selected_brands.value = [];
                    }
                });
            }
        });
}

function openEditBrandModal(id) {
    brandStore.edit_brand_id = id;
    showEditBrand.value = true;
}

function openViewBrandModal(id) {
    brandStore.view_brand_id = id;
    showViewBrand.value = true;
}

async function fetchData(
    page = brandStore.current_page,
    per_page = brandStore.per_page,
    q_name = brandStore.q_name
) {
    loading.value = true;

    all_selectd.value = false;
    selected_brands.value = [];

    try {
        brandStore.fetchBrands(page, per_page, q_name).then((response) => {
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
    <div v-if="authStore.userCan('view_brand')">
        <div class="page-top-box mb-2 d-flex flex-wrap">
            <h3 class="h3">Brands</h3>
            <div class="page-heading-actions ms-auto">
                <BulkDeleteButton
                    v-if="
                        selected_brands.length > 0 &&
                        authStore.userCan('delete_brand')
                    "
                    @click="deleteData(selected_brands)"
                />
                <AddNewButton
                    v-if="authStore.userCan('create_brand')"
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
                            @keyup="fetchData(1, brandStore.per_page, q_name)"
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
                        <th>Logo</th>
                        <th>Brand Name</th>
                        <th class="table-action-col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="brand in brands" :key="brand.id">
                        <td>
                            <input
                                type="checkbox"
                                class="form-check-input"
                                v-model="selected_brands"
                                :value="brand.id"
                            />
                        </td>

                        <td>
                            <div style="width: 50px; height: 50px">
                                <img
                                    :src="
                                        brand.logo[0]
                                            ? brand.logo[0]['url']
                                            : $demoIMG
                                    "
                                    :alt="brand.name"
                                    class="img-fluid"
                                />
                            </div>
                        </td>
                        <td>{{ brand.name }}</td>
                        <td class="table-action-btns">
                            <ViewSvgIcon
                                color="#00CFDD"
                                @click="openViewBrandModal(brand.id)"
                            />
                            <EditSvgIcon
                                v-if="authStore.userCan('update_brand')"
                                color="#739EF1"
                                @click="openEditBrandModal(brand.id)"
                            />
                            <BinSvgIcon
                                v-if="authStore.userCan('delete_brand')"
                                color="#FF7474"
                                @click="deleteData(brand.id)"
                            />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <Pagination
            v-if="loading == false && brands.length > 0"
            :total_pages="brandStore.total_pages"
            :current_page="brandStore.current_page"
            :per_page="brandStore.per_page"
            @pageChange="
                (currentPage) => fetchData(currentPage, brandStore.per_page)
            "
            @perPageChange="(perpage) => fetchData(1, perpage)"
        />
        <div class="modals-container">
            <AddBrand
                v-if="showAddBrand"
                @close="showAddBrand = false"
                @refreshData="fetchData(1)"
            />
            <EditBrand
                v-if="showEditBrand"
                :brand_id="brandStore.edit_brand_id"
                @close="showEditBrand = false"
                @refreshData="fetchData(brandStore.current_page)"
            />
            <ViewBrand
                v-if="showViewBrand"
                :brand_id="brandStore.view_brand_id"
                @close="showViewBrand = false"
            />
        </div>
    </div>
</template>
