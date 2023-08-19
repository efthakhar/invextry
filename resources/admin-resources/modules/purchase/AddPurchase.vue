<script setup>
import { onMounted, ref } from "vue";
import CrossSvgIcon from "../../assets/icons/cross-svg-icon.vue";
import router from "../../router";
import axios from "axios";
const warehouses = ref([]);
const products = ref([]);
const selected_products = ref([]);
const suppliers = ref([]);
const selected_warehouse = ref("");
const selected_supplier = ref({});
const purchase_date = ref("");
// const discount_type = "flat";
const discount = ref(0);
// const purchase_tax_id = ref("");
const purchase_tax = ref(0);
const total_purchase_tax = ref(0);
const shipping_cost = ref(0);
const purchase_grand_total = ref(0);
const paid_amount = ref(0);
const purchase_status = ref("ordered");
const payment_status = ref("unpaid");
const note = ref("");
const supplier_q = ref("");
const product_q = ref("");

async function fetchSuppliers(name = supplier_q.value) {
    if (name.length < 1) {
        clearSuppliers();
        return;
    }
    await axios
        .get(`/api/suppliers/search/${name}`)
        .then((response) => {
            suppliers.value = [];
            suppliers.value = response.data.data;
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
        .get(`/api/products-by-name/${name}`)
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

function clearSuppliers() {
    suppliers.value = [];
}
function clearProducts() {
    products.value = [];
}
function clearSelectedProducts() {
    selected_products.value = [];
    calculateGrandTotal();
}

function onSelectSupplier(supplier) {
    selected_supplier.value = supplier;
    supplier_q.value = supplier.name;
    clearSuppliers();
}

function onSelectWarehouse() {
    // clearProducts();
    // clearSelectedProducts();
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
    calculateGrandTotal();
}

function removeSelected(id) {
    selected_products.value = selected_products.value.filter((product) => {
        return id != product.id;
    });
    calculateGrandTotal();
}

function calculateGrandTotal() {
    let totalProductsCostWithTax = 0;
    let totalProductsCostWithoutTax = 0;
    total_purchase_tax.value = 0;
    purchase_grand_total.value = 0;

    selected_products.value.forEach((p) => {
        if (p.tax_type == "exclusive") {
            let item_total_with_tax =
                p.quantity *
                (p.purchase_price * (p.rate / 100) + p.purchase_price);
            let item_total_without_tax = p.quantity * p.purchase_price;
            totalProductsCostWithTax += item_total_with_tax;
            totalProductsCostWithoutTax += item_total_without_tax;
        } else {
            let item_total_with_tax = p.quantity * p.purchase_price;
            let item_total_without_tax =
                p.quantity * ((1 / (100 - p.rate)) * p.purchase_price);
            totalProductsCostWithTax += item_total_with_tax;
            totalProductsCostWithoutTax += item_total_without_tax;
        }
    });

    total_purchase_tax.value =
        totalProductsCostWithoutTax * (purchase_tax.value / 100);

    purchase_grand_total.value =
        shipping_cost.value +
        totalProductsCostWithTax -
        discount.value +
        total_purchase_tax.value;
}

function savePurchase() {
    let purchase = {
        products: selected_products.value,
        warehouse_id: selected_warehouse.value,
        supplier_id: selected_supplier.value.id,
        invoice_date: purchase_date.value,
        invoice_status: purchase_status.value,
        payment_status: payment_status.value,
        invoice_note: note.value,

        invoice_tax_rate: purchase_tax.value,
        shipping_cost: shipping_cost.value,
        discount: discount.value,
    };
    axios
        .post(`/api/purchases`, purchase)
        .then((response) => {
            router.push({ name: "purchase" });
        })
        .catch((error) => {
            console.log(error);
        });
}

onMounted(async () => {
    await fetchWarehouses();
});
</script>
<template>
    <div class="add-purchase-page">
        <div class="page-top-box d-flex flex-wrap align-items-center">
            <h3 class="h3">Add Purchase</h3>
        </div>
        <!-- Product, warehouse, supplier selection -->
        <div class="d-flex flex-wrap">
            <div class="p-1 min150">
                <label class="my-1">date</label>
                <input
                    type="date"
                    class="form-control form-control-sm"
                    v-model="purchase_date"
                />
            </div>
            <div class="p-1 dropdown-search-select-box min100 max200">
                <label class="my-1">supplier</label>
                <input
                    type="text"
                    class="form-control form-control-sm sqaure"
                    placeholder="search supplier.."
                    v-model="supplier_q"
                    @keyup="fetchSuppliers(supplier_q)"
                />
                <ul
                    class="list-group dropdown-search-list"
                    v-if="suppliers.length > 0"
                >
                    <li
                        @click="onSelectSupplier(c)"
                        :key="c.id"
                        class="list-group-item cursor-pointer"
                        v-for="c in suppliers"
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
        <!-- purchase items -->
        <table class="table bg-white table-bordered my-3 p-1 table-responsive">
            <thead>
                <tr class="bg-primary text-white">
                    <th class="min150">Product</th>
                    <th class="min100">Unit Price</th>
                    <th class="min100">Stock</th>
                    <!-- <th class="min100">Quantity</th> -->
                    <th class="min100">Tax</th>
                    <th class="min100">Subtotal</th>
                    <th class="min100">action</th>
                </tr>
            </thead>
            <tbody v-if="selected_products.length > 0">
                <tr v-for="p in selected_products">
                    <td>{{ p.name }}</td>
                    <td>{{ p.purchase_price }}</td>
                    <!-- <td>{{ p.stock_quanity ?? 0 }}</td> -->
                    <td>
                        <input
                            type="number"
                            class="max100 form-control"
                            min="1"
                            v-model="p.quantity"
                            @input="calculateGrandTotal()"
                        />
                    </td>
                    <td>
                        {{
                            p.tax_type == "exclusive"
                                ? (
                                      p.quantity *
                                      (p.purchase_price * (p.rate / 100))
                                  ).toFixed(2)
                                : (
                                      p.quantity *
                                      ((((100 - p.rate) * p.purchase_price) /
                                          100) *
                                          (p.rate / 100))
                                  ).toFixed(2)
                        }}
                        $
                    </td>
                    <td>
                        {{
                            p.tax_type == "exclusive"
                                ? p.quantity *
                                  (p.purchase_price * (p.rate / 100) +
                                      p.purchase_price)
                                : p.quantity * p.purchase_price
                        }}
                    </td>
                    <td>
                        <CrossSvgIcon
                            @click="removeSelected(p.id)"
                            color="red"
                        />
                    </td>
                </tr>
            </tbody>
        </table>
        <!-- Order Summary -->
        <div class="mt-1 4-3">
            <div class="invoice_summary mb-3 max250 ms-auto">
                <li class="list-group-item active">Order Summery</li>
                <li class="list-group-item">
                    <span class="text-primary">Order Tax:</span>
                    {{ total_purchase_tax.toFixed(2) }}
                </li>
                <li class="list-group-item">
                    <span class="text-primary">Discount:</span>
                    {{ discount.toFixed(2) }}
                </li>
                <li class="list-group-item">
                    <span class="text-primary">Shipping:</span>
                    {{ shipping_cost.toFixed(2) }}
                </li>
                <li class="list-group-item">
                    <span class="bold h6">Grand Total:</span>
                    {{ purchase_grand_total.toFixed(2) }}
                </li>
            </div>
        </div>

        <!-- purchase tax, discount, shipping-->
        <div class="row">
            <div class="input-group input-group-sm my-1 max250">
                <span class="input-group-text btn btn-primary">Order Tax</span>
                <input
                    type="number"
                    class="form-control"
                    min="0"
                    v-model="purchase_tax"
                    @input="calculateGrandTotal()"
                />
                <span class="input-group-text btn btn-primary">%</span>
            </div>
            <div class="input-group input-group-sm my-1 max250">
                <span class="input-group-text btn btn-primary">Discount</span>
                <input
                    type="number"
                    class="form-control"
                    min="0"
                    v-model="discount"
                    @input="calculateGrandTotal()"
                />
                <span class="input-group-text btn btn-primary">$</span>
            </div>
            <div class="input-group input-group-sm my-1 max250">
                <span class="input-group-text btn btn-primary">Shipping</span>
                <input
                    type="number"
                    class="form-control"
                    min="0"
                    v-model="shipping_cost"
                    @input="calculateGrandTotal()"
                />
                <span class="input-group-text btn btn-primary">$</span>
            </div>
        </div>

        <!-- Purchase status and Payment Status -->
        <div class="row my-3">
            <div class="p-2 max200">
                <label class="my-1">Purchase Status</label>
                <select
                    class="form-select form-select-sm"
                    v-model="purchase_status"
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
        <!-- Purchase Note -->
        <div class="row my-1">
            <div>
                <label class="my-2">Purchase Note</label>
                <textarea
                    v-model="note"
                    class="form-control"
                    rows="3"
                ></textarea>
            </div>
        </div>
        <div class="puchase_save my-4">
            <button
                class="btn btn-sm btn-primary d-inline"
                @click="savePurchase()"
            >
                Save Purchase
            </button>
        </div>
        <div class="modals-container"></div>
    </div>
</template>
