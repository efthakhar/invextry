<script setup>
import { onMounted, ref } from "vue";
import CrossSvgIcon from "../../assets/icons/cross-svg-icon.vue";
import axios from "axios";
const warehouses = ref([]);
const products = ref([]);
const selected_products = ref([]);
const customers = ref([]);
const selected_warehouse = ref("");
const selected_customer = ref("");
const sale_date = ref("");
// const discount_type = "flat";
const sale_discount = ref(0);
// const sale_tax_id = ref("");
const sale_tax = ref(0);
const shipping_amount = ref(0);
const total_amount = ref(0);
const paid_amount = ref(0);
const sale_status = ref("ordered");
const payment_status = ref("unpaid");
const note = ref("");
const customer_q = ref("");
const product_q = ref("");

async function fetchCustomers(name = customer_q.value) {
    if (name.length < 1) {
        clearCustomers();
        return;
    }
    await axios
        .get(`/api/customers/search/${name}`)
        .then((response) => {
            customers.value = [];
            customers.value = response.data.data;
        })
        .catch((errors) => {
            console.log(errors);
        });
}
async function fetchProducts(name = product_q.value) {
    if (name.length < 1) {
        clearProducts();
        return;
    }
    await axios
        .get(`/api/warehouse-products/${selected_warehouse.value}/${name}`)
        .then((response) => {
            products.value = [];
            products.value = response.data;
        })
        .catch((errors) => {
            console.log(errors.response);
        });
}

async function fetchWarehouses() {
    await axios
        .get(`/api/warehouses`)
        .then((response) => {
            warehouses.value = response.data.data;
        })
        .catch((errors) => {
            console.log(errors);
        });
}

function clearCustomers() {
    customers.value = [];
}
function clearProducts() {
    products.value = [];
}
function clearSelectedProducts() {
    selected_products.value = [];
}

function onSelectCustomer(customer) {
    selected_customer.value = customer;
    customer_q.value = customer.name;
    clearCustomers();
}

function onSelectWarehouse() {
    clearProducts();
    clearSelectedProducts();
}

function onSelectProduct(product) {
    const existingProduct = selected_products.value.find(
        (item) => item.id === product.id
    );

    if (existingProduct) {
        existingProduct.quantity += 1;
    } else {
        product.quantity = 1;
        selected_products.value.push(product);
    }
    clearProducts();
    product_q.value = "";
}

function removeSelected(id) {
    selected_products.value = selected_products.value.filter((product) => {
        return id != product.id;
    });
}

onMounted(async () => {
    await fetchWarehouses();
});
</script>
<template>
    <div class="add-sale-page">
        <div class="page-top-box d-flex flex-wrap align-items-center">
            <h3 class="h3">Add Sale</h3>
        </div>
        <!-- Product, warehouse, customer selection -->
        <div class="d-flex flex-wrap">
            <div class="p-1 min150">
                <label class="my-1">date</label>
                <input
                    type="date"
                    class="form-control form-control-sm"
                    v-model="sale_date"
                />
            </div>
            <div class="p-1 dropdown-search-select-box min100 max200">
                <label class="my-1">customer</label>
                <input
                    type="text"
                    class="form-control form-control-sm sqaure"
                    placeholder="search customer.."
                    v-model="customer_q"
                    @keyup="fetchCustomers(customer_q)"
                />
                <ul
                    class="list-group dropdown-search-list"
                    v-if="customers.length > 0"
                >
                    <li
                        @click="onSelectCustomer(c)"
                        :key="c.id"
                        class="list-group-item cursor-pointer"
                        v-for="c in customers"
                    >
                        {{ c.name }}
                    </li>
                </ul>
            </div>
            <div class="p-1 min150">
                <label class="my-1">warehouse</label>
                <select
                    class="form-select form-select-sm"
                    v-model="selected_warehouse"
                    :disabled="selected_products.length > 0"
                    @input="onSelectWarehouse()"
                >
                    <option value="">none</option>
                    <option :value="w.id" v-for="w in warehouses">
                        {{ w.name }}
                    </option>
                </select>
            </div>
            <div class="p-1 dropdown-search-select-box">
                <label class="my-1">search product</label>
                <input
                    :disabled="!selected_warehouse"
                    type="text"
                    class="form-control form-control-sm"
                    placeholder="search products.."
                    v-model="product_q"
                    @keyup="fetchProducts(product_q)"
                />
                <ul
                    class="list-group dropdown-search-list"
                    v-if="products.length > 0"
                >
                    <li
                        @click="onSelectProduct(p)"
                        :key="p.id"
                        class="list-group-item cursor-pointer"
                        v-for="p in products"
                    >
                        {{ p.name }}
                    </li>
                </ul>
            </div>
        </div>
        <!-- sale items -->
        <table class="table bg-white table-bordered my-5 p-1 table-responsive">
            <thead>
                <tr>
                    <th class="min150">Product</th>
                    <th class="min100">Unit Price</th>
                    <th class="min100">Stock</th>
                    <th class="min100">Quantity</th>
                    <th class="min100">Tax</th>
                    <th class="min100">Subtotal</th>
                    <th class="min100">action</th>
                </tr>
            </thead>
            <tbody v-if="selected_products.length > 0">
                <tr v-for="p in selected_products">
                    <td>{{ p.name }}</td>
                    <td>{{ p.sale_price }}</td>
                    <td>{{ p.stock_quanity ?? 0 }}</td>
                    <td>
                        <input
                            type="number"
                            class="max100 form-control"
                            min="1"
                            v-model="p.quantity"
                        />
                    </td>
                    <td>{{ p.rate }} %</td>
                    <td>{{ p.quantity * p.sale_price }}</td>
                    <td>
                        <CrossSvgIcon
                            @click="removeSelected(p.id)"
                            color="red"
                        />
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- sale tax, discount, shipping-->
        <div class="row">
            <div class="input-group input-group-sm my-1 max250">
                <span class="input-group-text btn btn-primary">Sale Tax</span>
                <input
                    type="number"
                    class="form-control"
                    min="0"
                    v-model="sale_tax"
                />
                <span class="input-group-text btn btn-primary">%</span>
            </div>
            <div class="input-group input-group-sm my-1 max250">
                <span class="input-group-text btn btn-primary">Discount</span>
                <input
                    type="number"
                    class="form-control"
                    min="0"
                    v-model="sale_discount"
                />
                <span class="input-group-text btn btn-primary">$</span>
            </div>
            <div class="input-group input-group-sm my-1 max250">
                <span class="input-group-text btn btn-primary">Shipping</span>
                <input
                    type="number"
                    class="form-control"
                    min="0"
                    v-model="shipping_amount"
                />
                <span class="input-group-text btn btn-primary">$</span>
            </div>
        </div>

        <!-- Sale status and Payment Status -->
        <div class="row my-4">
            <div class="p-2 max200">
                <label class="my-1">Sale Status</label>
                <select
                    class="form-select form-select-sm"
                    v-model="sale_status"
                >
                    <option value="ordered">ordered</option>
                    <option value="pending">pending</option>
                    <option value="completed">completed</option>
                </select>
            </div>
            <div class="p-2 max200">
                <label class="my-1">Payment Status</label>
                <select
                    class="form-select form-select-sm"
                    v-model="payment_status"
                >
                    <option value="unpaid">unpaid</option>
                    <option value="partial">partial</option>
                    <option value="paid">paid</option>
                </select>
            </div>
        </div>
        <div class="row my-1">
            <div>
                <label class="my-2">Sale Note</label>
                <textarea
                    v-model="sale_note"
                    class="form-control"
                    rows="3"
                ></textarea>
            </div>
        </div>
        <div class="mt-3 mb-5">
            <button class="btn btn-sm btn-primary d-inline">
                Save Sale
            </button>
        </div>

        <div class="modals-container"></div>
    </div>
</template>

<style>
.dropdown-search-select-box {
    position: relative;
}
.dropdown-search-list {
    position: absolute;
    top: 100%;
    left: 0;
    width: 100%;
    height: auto;
    z-index: 200;
}
.add-sale-page .form-select:focus {
    box-shadow: none !important;
}
.add-sale-page .table > :not(caption) > * > * {
    padding: 2px 5px;
    font-weight: normal;
}
.add-sale-page .dataTable-table thead th,
.add-sale-page .table thead th {
    border: 1px solid #ffffff;
    background-color: #5a8eeee7;
    color: white;
    padding: 8px 5px !important;
}
</style>
