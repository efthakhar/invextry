<script setup>
import { onMounted, ref } from "vue";
import CrossSvgIcon from "../../assets/icons/cross-svg-icon.vue";
import router from "../../router";
import axios from "axios";
import { useNotificationStore } from "../../components/shared/notification/notificationStore";
import formatValidationErrors from "../../utils/format-validation-errors";
const warehouses = ref([]);
const items = ref([]);
const selected_items = ref([]);
const customers = ref([]);
const accounts = ref([]);
const account_id = ref("");
const payment_method = ref("");
const selected_warehouse = ref("");
const selected_party = ref({});
const invoice_date = ref("");
const discount = ref(0);
const invoice_tax_rate = ref(0);
const total_invoice_tax = ref(0);
const shipping_cost = ref(0);
const invoice_grand_total = ref(0);
const paid_amount = ref(0);
const invoice_status = ref("ordered");
const payment_status = ref("unpaid");
const note = ref("");
const customer_q = ref("");
const product_q = ref("");
const validation_errors = ref([]);

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
            items.value = [];
            items.value = response.data;
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

async function fetchAccounts() {
    await axios
        .get(`/api/accounts/list`)
        .then((response) => {
            accounts.value = response.data.data;
        })
        .catch((errors) => {
            console.log(errors);
        });
}

function clearCustomers() {
    customers.value = [];
}
function clearProducts() {
    items.value = [];
}
function clearSelectedProducts() {
    selected_items.value = [];
    calculateGrandTotal();
}

function onSelectCustomer(customer) {
    selected_party.value = customer;
    customer_q.value = customer.name;
    clearCustomers();
}

function onSelectWarehouse() {
    // clearProducts();
    // clearSelectedProducts();
}

function onSelectProduct(product) {
    const existingProduct = selected_items.value.find(
        (item) => item.id === product.id
    );

    if (existingProduct) {
        existingProduct.quantity += 1;
    } else {
        product.quantity = 1;
        selected_items.value.push(product);
    }
    clearProducts();
    product_q.value = "";
    calculateGrandTotal();
}

function removeSelected(id) {
    selected_items.value = selected_items.value.filter((product) => {
        return id != product.id;
    });
    calculateGrandTotal();
}

function calculateGrandTotal() {
    let totalProductsCostWithTax = 0;
    let totalProductsCostWithoutTax = 0;
    total_invoice_tax.value = 0;
    invoice_grand_total.value = 0;

    selected_items.value.forEach((p) => {
        p.quantity > p.stock_quantity ? (p.quantity = p.stock_quantity) : "";

        if (p.tax_type == "exclusive") {
            let item_total_with_tax =
                p.quantity * (p.sale_price * (p.tax_rate / 100) + p.sale_price);
            let item_total_without_tax = p.quantity * p.sale_price;
            totalProductsCostWithTax += item_total_with_tax;
            totalProductsCostWithoutTax += item_total_without_tax;
        } else {
            let item_total_with_tax = p.quantity * p.sale_price;
            let item_total_without_tax =
                p.quantity * ((1 / (100 - p.tax_rate)) * p.sale_price);
            totalProductsCostWithTax += item_total_with_tax;
            totalProductsCostWithoutTax += item_total_without_tax;
        }
    });

    total_invoice_tax.value =
        totalProductsCostWithoutTax * (invoice_tax_rate.value / 100);

    invoice_grand_total.value =
        shipping_cost.value +
        totalProductsCostWithTax -
        discount.value +
        total_invoice_tax.value;

    // determining payment status
    if (paid_amount.value === 0) {
        payment_status.value = "unpaid";
    } else if (paid_amount.value === invoice_grand_total.value) {
        payment_status.value = "paid";
    } else if (paid_amount.value < invoice_grand_total.value) {
        payment_status.value = "partial";
    } else if (paid_amount.value > invoice_grand_total.value) {
        paid_amount.value = invoice_grand_total.value;
        payment_status.value = "paid";
        const notifcationStore = useNotificationStore();
        notifcationStore.pushNotification({
            message: "Paid amount can not be greater than total amount",
            type: "warning",
            time: 5000,
        });
    }
}

function saveSale() {
    let invoice = {
        items: selected_items.value,
        warehouse_id: selected_warehouse.value,
        party_id: selected_party.value.id,
        invoice_date: invoice_date.value,
        invoice_status: invoice_status.value,
        payment_status: payment_status.value,
        paid_amount: paid_amount.value,
        payment_method: payment_method.value,
        account_id: account_id.value,
        note: note.value,
        invoice_tax_rate: invoice_tax_rate.value,
        shipping_cost: shipping_cost.value,
        discount: discount.value,
    };
    axios
        .post(`/api/sales`, invoice)
        .then((response) => {
            router.push({ name: "sale" });
        })
        .catch((error) => {
            const notifcationStore = useNotificationStore();
            notifcationStore.pushNotification({
                message: "Error Occurred",
                type: "error",
                time: 3000,
            });

            if (error.response.status == 422) {
                validation_errors.value = formatValidationErrors(
                    error.response.data.errors
                );
            }
            //console.log(error);
        });
}

onMounted(async () => {
    await fetchWarehouses();
    await fetchAccounts();
});
</script>
<template>
    <div class="add-invoice-page">
        <div class="page-top-box d-flex flex-wrap align-items-center">
            <h3 class="h5">Add New Sale</h3>
        </div>
        <div class="add-invoice-contents bg-white p-3 my-3 rounded-3 shadow">
            <!-- Product, warehouse, customer selection -->
            <div class="d-flex flex-wrap">
                <div class="p-1 min150">
                    <label class="my-1">date</label>
                    <input
                        type="date"
                        class="form-control form-control-sm"
                        v-model="invoice_date"
                    />
                    <span class="v-error" v-if="validation_errors.invoice_date">
                        {{ validation_errors.invoice_date }}
                    </span>
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
                    <span class="v-error" v-if="validation_errors.party_id">
                        {{ validation_errors.party_id }}
                    </span>
                </div>
                <div class="p-1 min150">
                    <label class="my-1">warehouse</label>
                    <select
                        class="form-select form-select-sm"
                        v-model="selected_warehouse"
                        @input="onSelectWarehouse()"
                        :disabled="selected_items.length > 0 || items.length>0"
                    >
                        <option value="">none</option>
                        <option :value="w.id" v-for="w in warehouses">
                            {{ w.name }}
                        </option>
                    </select>
                    <span class="v-error" v-if="validation_errors.warehouse_id">
                        {{ validation_errors.warehouse_id }}
                    </span>
                </div>
                <div class="p-1 dropdown-search-select-box">
                    <label class="my-1">search product</label>
                    <input
                        :disabled="!selected_warehouse"
                        type="text"
                        class="form-control form-control-sm"
                        placeholder="search items.."
                        v-model="product_q"
                        @keyup="fetchProducts(product_q)"
                    />
                    <ul
                        class="list-group dropdown-search-list"
                        v-if="items.length > 0"
                    >
                        <li
                            @click="onSelectProduct(p)"
                            :key="p.id"
                            class="list-group-item cursor-pointer"
                            v-for="p in items"
                        >
                            {{ p.name }}
                        </li>
                    </ul>
                </div>
            </div>
            <!-- invoice items -->
            <div class="table-responsive">
                <table
                    class="table bg-white table-bordered my-3 p-1 table-responsive"
                >
                    <thead>
                        <tr class="bg-ass text-secondary">
                            <th class="min150">Product</th>
                            <th class="min100">Unit Price</th>
                            <th class="">Stock</th>
                            <th class="min100">Quantity</th>
                            <th class="min100">Tax</th>
                            <th class="min100">Subtotal</th>
                            <th class="min100">action</th>
                        </tr>
                    </thead>
                    <tbody v-if="selected_items.length > 0">
                        <tr v-for="p in selected_items">
                            <td>{{ p.name }}</td>
                            <td>{{ p.sale_price }}</td>
                            <td>
                                <input
                                    type="number"
                                    class="max100 form-control"
                                    :value="p.stock_quantity"
                                    disabled
                                />
                            </td>
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
                                              (p.sale_price *
                                                  (p.tax_rate / 100))
                                          ).toFixed(2)
                                        : (
                                              p.quantity *
                                              ((((100 - p.tax_rate) *
                                                  p.sale_price) /
                                                  100) *
                                                  (p.tax_rate / 100))
                                          ).toFixed(2)
                                }}
                                $
                            </td>
                            <td>
                                {{
                                    p.tax_type == "exclusive"
                                        ? p.quantity *
                                          (p.sale_price * (p.tax_rate / 100) +
                                              p.sale_price)
                                        : p.quantity * p.sale_price
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
            </div>
            <span class="v-error" v-if="validation_errors.items">
                {{ validation_errors.items }}
            </span>
            <!-- Order Summary -->
            <div class="mt-1 4-3">
                <div class="invoice_summary mb-3 max250 ms-auto">
                    <li class="list-group-item bg-ass text-secondary">
                        Order Summary
                    </li>
                    <li class="list-group-item">
                        <span class="text-primary">Order Tax:</span>
                        {{ total_invoice_tax.toFixed(2) }}
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
                        <span class="text-primary">Paid:</span>
                        {{ paid_amount.toFixed(2) }}
                    </li>
                    <li class="list-group-item">
                        <span class="text-primary">Due:</span>
                        {{ (invoice_grand_total - paid_amount).toFixed(2) }}
                    </li>
                    <li class="list-group-item bg-ass text-secondary">
                        <span class="bold h6">Grand Total:</span>
                        {{ invoice_grand_total.toFixed(2) }}
                    </li>
                </div>
            </div>

            <!-- invoice tax, discount, shipping-->
            <div class="row">
                <div class="input-group input-group-sm my-1 max250">
                    <span class="input-group-text bg-ass text-secondary"
                        >Order Tax</span
                    >
                    <input
                        type="number"
                        class="form-control"
                        min="0"
                        v-model="invoice_tax_rate"
                        @input="calculateGrandTotal()"
                    />
                    <span class="input-group-text bg-ass text-secondary"
                        >%</span
                    >
                </div>
                <div class="input-group input-group-sm my-1 max250">
                    <span class="input-group-text bg-ass text-secondary"
                        >Discount</span
                    >
                    <input
                        type="number"
                        class="form-control"
                        min="0"
                        v-model="discount"
                        @input="calculateGrandTotal()"
                    />
                    <span class="input-group-text bg-ass text-secondary"
                        >$</span
                    >
                </div>
                <div class="input-group input-group-sm my-1 max250">
                    <span class="input-group-text bg-ass text-secondary"
                        >Shipping</span
                    >
                    <input
                        type="number"
                        class="form-control"
                        min="0"
                        v-model="shipping_cost"
                        @input="calculateGrandTotal()"
                    />
                    <span class="input-group-text bg-ass text-secondary"
                        >$</span
                    >
                </div>
            </div>

            <!-- Sale status and Payment Status -->
            <div class="row my-3">
                <div class="p-2 max200">
                    <label class="my-1">Sale Status</label>
                    <select
                        class="form-select form-select-sm"
                        v-model="invoice_status"
                    >
                        <option value="received">received</option>
                        <option value="pending">pending</option>
                        <option value="ordered">ordered</option>
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
                <div class="p-2 max200">
                    <label class="my-1">Paid Amount</label>
                    <input
                        type="number"
                        class="form-control"
                        min="0"
                        v-model="paid_amount"
                        @input="calculateGrandTotal()"
                    />
                </div>
                <div class="p-2 max200">
                    <label class="my-1">Select Account</label>
                    <select
                        class="form-select form-select-sm"
                        v-model="account_id"
                    >
                        <option value="">none</option>
                        <option :value="account.id" v-for="account in accounts">
                            {{ account.name }} -- {{ account.balance }}
                        </option>
                    </select>
                    <span class="v-error" v-if="validation_errors.account_id">
                        {{ validation_errors.account_id }}
                    </span>
                </div>
                <div class="p-2 max200">
                    <label class="my-1">Payment Method</label>
                    <select
                        class="form-select form-select-sm"
                        v-model="payment_method"
                    >
                        <option value="">none</option>
                        <option value="cash">cash</option>
                        <option value="payoneer">payoneer</option>
                        <option value="wise">wise</option>
                        <option value="bank">bank</option>
                        <option value="paypal">paypal</option>
                        <option value="card">card</option>
                    </select>
                    <span
                        class="v-error"
                        v-if="validation_errors.payment_method"
                    >
                        {{ validation_errors.payment_method }}
                    </span>
                </div>
            </div>
            <!-- Sale Note -->
            <div class="row my-1">
                <div>
                    <label class="my-2">Sale Note</label>
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
                    @click="saveSale()"
                >
                    Save Sale
                </button>
            </div>
        </div>
    </div>
</template>
