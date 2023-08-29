<script setup>
import { ref, onMounted } from "vue";
import Loader from "../../components/shared/loader/Loader.vue";
import axios from "axios";
import CartSvgIcon from "../../assets/icons/cart-svg-icon.vue";
import BegSvgIcon from "../../assets/icons/beg-svg-icon.vue";
import WalletSvgIcon from "../../assets/icons/wallet-svg-icon.vue";
import CoinSvgIcon from "../../assets/icons/coin-1-svg-icon.vue";
import twentyfourSVGICon from "../../assets/icons/twentyfour-svg-icon.vue";
import HandLoveSVGICon from "../../assets/icons/hand-love-svg-icon.vue";
import VueApexCharts from "vue3-apexcharts";

const loading = ref(false);
const data = ref({});

let weeklySalePurchaseChartData = ref({
    chartOptions: {
        title: {
            text: "Weekly Sale & Purchase",
            align: "left",
            style: { color: "#475f7b" },
        },
        chart: { id: "weeklySalePurchase" },
        xaxis: { categories: [] },
        dataLabels: { enabled: false },
        colors: ["#41b1f9", "#3366CC"],
    },
    series: [
        { name: "sale", data: [] },
        { name: "purchase", data: [] },
    ],
});

let topSellingProductChartData = ref({
    chartOptions: {
        title: {
            text: "Top Selling Products",
            align: "left",
            style: { color: "#475f7b" },
        },
        chart: { id: "topSellingProduct" },
        labels: [],
        dataLabels: { enabled: true },
        colors: ["#41b1f9", "#8390FA", "#3366CC"],
        legend: {
            show: true,
            position: "bottom",
            horizontalAlign: "center",
        },
    },
    series: [],
});

let weeklyPaymentChartData = ref({
    chartOptions: {
        title: {
            text: "Payment Send & Received This Week",
            align: "left",
            style: { color: "#475f7b" },
        },
        chart: {
            id: "weeklyPayment",
            zoom: { enabled: false },
            selection: { enabled: false },
        },
        xaxis: { categories: [] },
        dataLabels: { enabled: false },
        colors: ["#41b1f9", "#3366CC"],
        stroke: {
            curve: "smooth",
        },
        markers: {
            size: 6,
        },
    },
    series: [
        { name: "Payment Send", data: [] },
        { name: "Payment Received", data: [] },
    ],
});

async function fetchData() {
    loading.value = true;
    await axios
        .get(`/api/dashboard-report`)
        .then((response) => {
            data.value = response.data;

            // weekly sale and purchase data
            weeklySalePurchaseChartData.value.chartOptions.xaxis.categories = [
                ...new Set(
                    response.data.current_week_sales
                        .map((sale) => sale.date)
                        .concat(
                            response.data.current_week_purchases.map(
                                (purchase) => purchase.date
                            )
                        )
                ),
            ];
            weeklySalePurchaseChartData.value.series[0].data =
                response.data.current_week_sales.map((sale) =>
                    sale.amount.toFixed(0)
                );
            weeklySalePurchaseChartData.value.series[1].data =
                response.data.current_week_purchases.map((purchase) =>
                    purchase.amount.toFixed(0)
                );

            // top selling products data
            topSellingProductChartData.value.series =
                response.data.top_selling_products.map(
                    (product) => product.total_quantity_sold
                );
            topSellingProductChartData.value.chartOptions.labels =
                response.data.top_selling_products.map((product) =>
                    product.name.substring(0, 12)
                );

            // payments current week
            weeklyPaymentChartData.value.chartOptions.xaxis.categories = [
                ...new Set(
                    response.data.payment_send_current_week
                        .map((payment) => payment.date)
                        .concat(
                            response.data.payment_received_current_week.map(
                                (payment) => payment.date
                            )
                        )
                ),
            ];
            weeklyPaymentChartData.value.series[0].data =
                response.data.payment_send_current_week.map((payment) =>
                    payment.amount.toFixed(0)
                );
            weeklyPaymentChartData.value.series[1].data =
                response.data.payment_received_current_week.map((payment) =>
                    payment.amount.toFixed(0)
                );

            loading.value = false;
        })
        .catch((errors) => {
            console.log(errors);
            loading.value = false;
        });
}

onMounted(() => {
    fetchData();
});
</script>

<template>
    <div class="dashboard-page">
        <Loader v-if="loading" />
        <div class="dashboard-page-contents mx-2" v-if="loading == false">
            <div class="dashboard-top-stats row flex-wrap">
                <div class="col-md-3 col-sm-6 my-1 p-1 min150">
                    <div
                        class="bg-white shadow d-flex flex-wrap rounded-3 p-3 align-items-start"
                    >
                        <div class="bg-info p-3 rounded-3 me-4 shadow">
                            <CartSvgIcon color="white" width="28" height="28" />
                        </div>
                        <div class="my-2">
                            <span class="top-stats-value">{{
                                data.total_sale_amount
                                    ? data.total_sale_amount.toFixed(0)
                                    : 0
                            }}</span
                            ><br /><span>Total Sale</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 my-1 p-1 min150">
                    <div
                        class="bg-white shadow d-flex flex-wrap rounded-3 p-3 align-items-start shadow"
                    >
                        <div class="bg-info p-3 rounded-3 me-4">
                            <BegSvgIcon color="white" width="28" height="28" />
                        </div>
                        <div class="my-2">
                            <span class="top-stats-value">{{
                                data.total_purchase_amount
                                    ? data.total_purchase_amount.toFixed(0)
                                    : 0
                            }}</span
                            ><br /><span>Total Purchase</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 my-1 p-1 min150">
                    <div
                        class="bg-white shadow d-flex flex-wrap rounded-3 p-3 align-items-start shadow"
                    >
                        <div class="bg-info p-3 rounded-3 me-4">
                            <WalletSvgIcon
                                color="white"
                                width="28"
                                height="28"
                            />
                        </div>
                        <div class="my-2">
                            <span class="top-stats-value">{{
                                data.total_sale_due
                                    ? data.total_sale_due.toFixed(0)
                                    : 0
                            }}</span
                            ><br /><span>Total Sale Due</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 my-1 p-1 min150">
                    <div
                        class="bg-white shadow d-flex flex-wrap rounded-3 p-3 align-items-start shadow"
                    >
                        <div class="bg-info p-3 rounded-3 me-4">
                            <CoinSvgIcon color="white" width="28" height="28" />
                        </div>
                        <div class="my-2">
                            <span class="top-stats-value">{{
                                data.total_purchase_due
                                    ? data.total_purchase_due.toFixed(0)
                                    : 0
                            }}</span
                            ><br /><span>Total Purchase Due</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="dashboard-charts my-3 row">
                <div class="col-md-8 p-1">
                    <VueApexCharts
                        class="bg-white shadow px-1 py-3 rounded-2"
                        width="100%"
                        height="350"
                        type="bar"
                        :options="weeklySalePurchaseChartData.chartOptions"
                        :series="weeklySalePurchaseChartData.series"
                    />
                </div>
                <div class="col-md-4 p-1">
                    <VueApexCharts
                        class="bg-white shadow px-1 py-3 rounded-5"
                        style="height: 100%"
                        width="100%"
                        type="donut"
                        height="350"
                        :options="topSellingProductChartData.chartOptions"
                        :series="topSellingProductChartData.series"
                    />
                </div>
            </div>
            <div class="dashboard-charts my-3 row">
                <div class="col-md-12 p-1">
                    <VueApexCharts
                        class="bg-white shadow px-1 py-3 rounded-2"
                        width="100%"
                        height="350"
                        type="line"
                        :options="weeklyPaymentChartData.chartOptions"
                        :series="weeklyPaymentChartData.series"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<style>
.dashboard-top-stats > *>*{
    height: 100%;
}
.top-stats-value{
    font-size: 20px;
    font-weight: 600;
    color:#475f7b;
}
</style>
