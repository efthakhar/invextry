<script setup>
import { ref, computed, onMounted } from "vue";
import CrossSvgIcon from "../../assets/icons/cross-svg-icon.vue";
import Loader from "../../components/shared/loader/Loader.vue";
import { useSupplierStore } from "./supplierStore";

const props = defineProps(["supplier_id"]);
const emit = defineEmits(["close"]);

const loading = ref(false);
const supplierStore = useSupplierStore();
const supplier_data = computed(() => supplierStore.current_supplier_item);

async function fetchData(id) {
    loading.value = true;
    await supplierStore.fetchSupplier(id);
    loading.value = false;
}

async function closeViewSupplierModal() {
    supplierStore.resetCurrentSupplierData();
    emit("close");
}

onMounted(() => {
    fetchData(props.supplier_id);
});
</script>

<template>
    <div class="modal fade show d-block">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Supplier Details</h5>
                    <button type="button" class="close">
                        <CrossSvgIcon @click="closeViewSupplierModal" />
                    </button>
                </div>

                <div class="modal-body">
                    <Loader v-if="loading" />
                    <div class="form-items" v-if="loading == false">
                        <form action="">
                            <div class="form-item mt-4">
                                <label class="my-2">Staus</label>
                                <div class="d-flex">
                                    <span class="form-check">
                                        <input
                                            disabled
                                            class="form-check-input"
                                            type="radio"
                                            v-model="supplier_data.status"
                                            id="active"
                                            value="active"
                                            :checked="
                                                supplier_data.status == 'active'
                                            "
                                        />
                                        <label
                                            class="form-check-label"
                                            for="active"
                                        >
                                            Active
                                        </label>
                                    </span>
                                    <span class="form-check ms-2">
                                        <input
                                            disabled
                                            class="form-check-input"
                                            type="radio"
                                            v-model="supplier_data.status"
                                            id="disabled"
                                            value="disabled"
                                            :checked="
                                                supplier_data.status ==
                                                'disabled'
                                            "
                                        />
                                        <label
                                            class="form-check-label"
                                            for="disabled"
                                        >
                                            Disabled
                                        </label>
                                    </span>
                                </div>
                            </div>
                            <div class="form-item">
                                <label class="my-2">Name</label>

                                <input
                                    disabled
                                    type="text"
                                    class="form-control"
                                    v-model="supplier_data.name"
                                />
                            </div>
                            <div class="form-item">
                                <label class="my-2">Email</label>

                                <input
                                    disabled
                                    type="email"
                                    class="form-control"
                                    v-model="supplier_data.email"
                                />
                            </div>
                            <div class="form-item">
                                <label class="my-2">Phone</label>

                                <input
                                    disabled
                                    type="tel"
                                    class="form-control"
                                    v-model="supplier_data.phone"
                                />
                            </div>
                            <div class="form-item">
                                <label class="my-2">Tax Number</label>

                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="supplier_data.tax_number"
                                />
                            </div>
                            <div class="form-item">
                                <label class="my-2">Country</label>

                                <input
                                    disabled
                                    type="text"
                                    class="form-control"
                                    v-model="supplier_data.country"
                                />
                            </div>
                            <div class="form-item">
                                <label class="my-2">City</label>

                                <input
                                    disabled
                                    type="text"
                                    class="form-control"
                                    v-model="supplier_data.city"
                                />
                            </div>
                            <div class="form-item">
                                <label class="my-2">Postal Code</label>

                                <input
                                    disabled
                                    type="text"
                                    class="form-control"
                                    v-model="supplier_data.postal_code"
                                />
                            </div>
                            <div class="form-item mt-4">
                                <label class="my-2">Address</label>
                                <textarea
                                    disabled
                                    v-model="supplier_data.address"
                                    class="form-control"
                                    rows="3"
                                ></textarea>
                            </div>
                            <div class="form-item mt-4">
                                <label class="my-2">Billing Address</label>
                                <textarea
                                    disabled
                                    v-model="supplier_data.billing_address"
                                    class="form-control"
                                    rows="3"
                                ></textarea>
                            </div>
                            <div class="form-item mt-4">
                                <label class="my-2">Shipping Address</label>
                                <textarea
                                    disabled
                                    v-model="supplier_data.shipping_address"
                                    class="form-control"
                                    rows="3"
                                ></textarea>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
