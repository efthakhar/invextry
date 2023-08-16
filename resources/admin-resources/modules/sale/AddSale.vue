<script setup>
import { ref } from "vue";

const warehouses = ref([]);
const products = ref([]);
const selected_products = ref([]);
const customers = ref([]);
const selected_warehouse_id = ref("");
const selected_customer_id = ref("");
const sale_date = ref("");
const discount_type = "flat";
const discount = ref(0);
const sale_tax_id = ref("");
const total_sale_tax = ref(0);
const total_amount = ref(0);
const paid_amount = ref(0);
const sale_status = ref("ordered");
const payment_status = ref("unpaid");
const note = ref("");
const customer_q = ref("");
const product_q = ref("");

function fetchCustomers(name = customer_q.value) {
    if (name.length < 3) {
        return;
    }
}
</script>
<template>
    <h3>add sales</h3>
</template>
