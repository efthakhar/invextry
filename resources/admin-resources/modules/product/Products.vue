<script setup>
import { computed, onBeforeMount, onMounted, ref } from "vue";
import { useAuthStore } from "../../stores/authStore";
import Loader from "../../components/shared/loader/Loader.vue";
import Pagination from "../../components/shared/pagination/Pagination.vue";
import { useConfirmStore } from "../../components/shared/confirm-alert/confirmStore.js";
import { useProductStore } from "./productStore";
import { useBrandStore } from "../brand/brandStore";
import { useProductCategoryStore } from "../product-category/productCategoryStore";
import { useTaxStore } from "../tax/taxStore";
import { useUnitStore } from "../unit/unitStore";
import BinSvgIcon from "../../assets/icons/bin-svg-icon.vue";
import EditSvgIcon from "../../assets/icons/edit-svg-icon.vue";
import ViewSvgIcon from "../../assets/icons/view-svg-icon.vue";
import AddNewButton from "../../components/buttons/AddNewButton.vue";
import FilterButton from "../../components/buttons/FilterButton.vue";
import BulkDeleteButton from "../../components/buttons/BulkDeleteButton.vue";
import AddProduct from "./AddProduct.vue";
import EditProduct from "./EditProduct.vue";
import ViewProduct from "./ViewProduct.vue";

const loading = ref(false);
const filterTab = ref(true);
const showAddProduct = ref(false);
const showEditProduct = ref(false);
const showViewProduct = ref(false);

const confirmStore = useConfirmStore();
const authStore = useAuthStore();

const productStore = useProductStore();
const products = computed(() => productStore.products);

const productCategoryStore = useProductCategoryStore();
const categories = ref([]);

const brandStore = useBrandStore();
const brands = ref([]);

const taxStore = useTaxStore();
const taxes = ref([]);

const unitStore = useUnitStore();
const base_units = ref([]);

const q_name = ref("");
const selected_products = ref([]);
const all_selectd = ref(false);

function select_all() {
    if (all_selectd.value == false) {
        selected_products.value = [];
        productStore.products.forEach((element) => {
            selected_products.value.push(element.id);
        });
        all_selectd.value = true;
    } else {
        all_selectd.value = false;
        selected_products.value = [];
    }
}

async function deleteData(id) {
    confirmStore
        .show_box({ message: "Do you want to delete selected product?" })
        .then(async () => {
            if (confirmStore.do_action == true) {
                productStore.deleteProduct(id).then(() => {
                    productStore.fetchProducts(
                        productStore.current_page,
                        productStore.per_page,
                        productStore.q_name
                    );

                    if (Array.isArray(id)) {
                        all_selectd.value = false;
                        selected_products.value = [];
                    }
                });
            }
        });
}

function openEditProductModal(id) {
    productStore.edit_product_id = id;
    showEditProduct.value = true;
}

function openViewProductModal(id) {
    productStore.view_product_id = id;
    showViewProduct.value = true;
}

async function fetchData(
    page = productStore.current_page,
    per_page = productStore.per_page,
    q_name = productStore.q_name
) {
    loading.value = true;

    all_selectd.value = false;
    selected_products.value = [];

    try {
        productStore.fetchProducts(page, per_page, q_name).then((response) => {
            loading.value = false;
        });
    } catch (error) {
        // console.log(error);
        loading.value = false;
    }
}

onMounted(() => {});

onBeforeMount(async () => {
    await fetchData(1);
    await productCategoryStore
        .fetchProductCategoryList()
        .then((response) => (categories.value = response));
    await brandStore
        .fetchBrandList()
        .then((response) => (brands.value = response));
    await taxStore.fetchTaxList().then((response) => (taxes.value = response));
    await unitStore
        .fetchBaseUnits()
        .then((response) => (base_units.value = response));
});
</script>

<template>
    <div v-if="authStore.userCan('view_product')">
        <div class="page-top-box mb-2 d-flex flex-wrap">
            <h3 class="h3">Products</h3>
            <div class="page-heading-actions ms-auto">
                <BulkDeleteButton
                    v-if="
                        selected_products.length > 0 &&
                        authStore.userCan('delete_product')
                    "
                    @click="deleteData(selected_products)"
                />
                <AddNewButton
                    v-if="authStore.userCan('create_product')"
                    @click="showAddProduct = true"
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
                            @keyup="fetchData(1, productStore.per_page, q_name)"
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
                        <th>Image</th>
                        <th class="max200 min200">Name</th>
                        <!-- <th class="">Code</th> -->
                        <th class="min100">Category</th>
                        <th class="min100">Brand</th>
                        <th class="min100">Cost</th>
                        <th class="min100">Price</th>
                        <!-- <th>Unit</th> -->
                        <th class="min100">Stock</th>
                        <th class="table-action-col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="product in products" :key="product.id">
                        <td>
                            <input
                                type="checkbox"
                                class="form-check-input"
                                v-model="selected_products"
                                :value="product.id"
                            />
                        </td>

                        <td>
                            <div style="width: 46px; height: 46px">
                                <img
                                    :src="
                                        product.gallery[0]
                                            ? product.gallery[0]['url']
                                            : $demoIMG
                                    "
                                    :alt="product.name"
                                    class="img-fluid"
                                />
                            </div>
                        </td>
                        <td>{{ product.name }}</td>
                        <!-- <td>{{ product.code }}</td> -->
                        <td>{{ product.category ?? "--" }}</td>
                        <td>{{ product.brand ?? "--" }}</td>
                        <td>{{ product.purchase_price }}</td>
                        <td>{{ product.sale_price }}</td>
                        <!-- <td>{{ product.unit.name }}</td> -->
                        <td>
                            {{ product.stock_quantity ?? 0 }}
                            {{ product.unit }}
                        </td>
                        <td class="table-action-btns">
                            <ViewSvgIcon
                                color="#00CFDD"
                                @click="openViewProductModal(product.id)"
                            />
                            <EditSvgIcon
                                v-if="authStore.userCan('update_product')"
                                color="#739EF1"
                                @click="openEditProductModal(product.id)"
                            />
                            <BinSvgIcon
                                v-if="authStore.userCan('delete_product')"
                                color="#FF7474"
                                @click="deleteData(product.id)"
                            />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <Pagination
            v-if="loading == false && products.length > 0"
            :total_pages="productStore.total_pages"
            :current_page="productStore.current_page"
            :per_page="productStore.per_page"
            @pageChange="
                (currentPage) => fetchData(currentPage, productStore.per_page)
            "
            @perPageChange="(perpage) => fetchData(1, perpage)"
        />
        <div class="modals-container">
            <AddProduct
                v-if="showAddProduct"
                @close="showAddProduct = false"
                @refreshData="fetchData(1)"
                :brands="brands"
                :categories="categories"
                :taxes="taxes"
                :base_units="base_units"
            />
            <EditProduct
                v-if="showEditProduct"
                :product_id="productStore.edit_product_id"
                @close="showEditProduct = false"
                @refreshData="fetchData(productStore.current_page)"
                :brands="brands"
                :categories="categories"
                :taxes="taxes"
                :base_units="base_units"
            />
            <ViewProduct
                v-if="showViewProduct"
                :product_id="productStore.view_product_id"
                @close="showViewProduct = false"
            />
        </div>
    </div>
</template>
