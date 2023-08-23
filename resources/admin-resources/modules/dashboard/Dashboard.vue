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
        colors: ['#41b1f9', '#3366CC'],
    },
    series: [
        { name: "sale", data: [] },
        { name: "purchase", data: [] },
    ],
    
});

async function fetchData() {
    loading.value = true;
    await axios
        .get(`/api/dashboard-report`)
        .then((response) => {
            data.value = response.data;

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
                        class="bg-white shadow-sm d-flex flex-wrap rounded-3 p-3 align-items-center"
                    >
                        <div class="bg-info p-3 rounded-3 me-4">
                            <CartSvgIcon color="white" width="28" height="28" />
                        </div>
                        <div class="my-2">
                            <span class="h3">{{
                                data.total_sale_amount
                                    ? data.total_sale_amount.toLocaleString(
                                          "en-US",
                                          {
                                              style: "currency",
                                              currency: "USD",
                                          }
                                      )
                                    : 0
                            }}</span
                            ><br /><span>Total Sale</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 my-1 p-1 min150">
                    <div
                        class="bg-white shadow-sm d-flex flex-wrap rounded-3 p-3 align-items-center"
                    >
                        <div class="bg-info p-3 rounded-3 me-4">
                            <BegSvgIcon color="white" width="28" height="28" />
                        </div>
                        <div class="my-2">
                            <span class="h3">{{
                                data.total_purchase_amount
                                    ? data.total_purchase_amount.toLocaleString(
                                          "en-US",
                                          {
                                              style: "currency",
                                              currency: "USD",
                                          }
                                      )
                                    : 0
                            }}</span
                            ><br /><span>Total Purchase</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 my-1 p-1 min150">
                    <div
                        class="bg-white shadow-sm d-flex flex-wrap rounded-3 p-3 align-items-center"
                    >
                        <div class="bg-info p-3 rounded-3 me-4">
                            <WalletSvgIcon
                                color="white"
                                width="28"
                                height="28"
                            />
                        </div>
                        <div class="my-2">
                            <span class="h3">{{
                                data.total_sale_due
                                    ? data.total_sale_due.toLocaleString(
                                          "en-US",
                                          {
                                              style: "currency",
                                              currency: "USD",
                                          }
                                      )
                                    : 0
                            }}</span
                            ><br /><span>Total Sale Due</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 my-1 p-1 min150">
                    <div
                        class="bg-white shadow-sm d-flex flex-wrap rounded-3 p-3 align-items-center"
                    >
                        <div class="bg-info p-3 rounded-3 me-4">
                            <CoinSvgIcon color="white" width="28" height="28" />
                        </div>
                        <div class="my-2">
                            <span class="h3">{{
                                data.total_purchase_due
                                    ? data.total_purchase_due.toLocaleString(
                                          "en-US",
                                          {
                                              style: "currency",
                                              currency: "USD",
                                          }
                                      )
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
                        width="100%"
                        height="350"
                        type="bar"
                        :options="weeklySalePurchaseChartData.chartOptions"
                        :series="weeklySalePurchaseChartData.series"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
