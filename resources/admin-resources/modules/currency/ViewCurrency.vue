<script setup>
import { ref, computed, onMounted } from "vue";

import CrossSvgIcon from "../../assets/icons/cross-svg-icon.vue";
import Loader from "../../components/shared/loader/Loader.vue";

import { useCurrencyStore } from "./currencyStore";

const props = defineProps(["currency_id"]);
const emit = defineEmits(["close"]);

const loading = ref(false);
const currencyStore = useCurrencyStore();
const currency_data = computed(() => currencyStore.current_currency_item);

async function fetchData(id) {
    loading.value = true;
    await currencyStore.fetchCurrency(id);
    loading.value = false;
}

async function closeViewCurrencyModal() {
    currencyStore.resetCurrentCurrencyData();
    emit("close");
}

onMounted(() => {
    fetchData(props.currency_id);
});
</script>

<template>
    <div class="modal fade show d-block" role="dialog" aria-modal="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Currency Details</h5>
                    <button type="button" class="close">
                        <CrossSvgIcon @click="closeViewCurrencyModal" />
                    </button>
                </div>
                <div class="modal-body pb-5">
                    <Loader v-if="loading" />
                    <div class="form-items" v-if="loading == false">
                        <label class="my-2">Currency Name</label>
                        <input
                            disabled
                            type="text"
                            class="form-control"
                            v-model="currency_data.name"
                        />

                        <label class="my-2">Currency Code</label>
                        <input
                            disabled
                            type="text"
                            class="form-control"
                            v-model="currency_data.code"
                        />

                        <label class="my-2">Currency Symbol</label>
                        <input
                            disabled
                            type="text"
                            class="form-control"
                            v-model="currency_data.symbol"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
