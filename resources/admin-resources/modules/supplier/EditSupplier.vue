<script setup>
import { ref, computed, onMounted } from "vue";
import CrossSvgIcon from "../../assets/icons/cross-svg-icon.vue";
import Loader from "../../components/shared/loader/Loader.vue";
import { useSupplierStore } from "./supplierStore";

const props = defineProps(["supplier_id"]);
const emit = defineEmits(["close", "refreshData"]);

const loading = ref(false);
const supplierStore = useSupplierStore();
const supplier_data = computed(() => supplierStore.current_supplier_item);

async function submitData() {
    supplierStore
        .editSupplier(
            JSON.parse(JSON.stringify(supplierStore.current_supplier_item))
        )
        .then(() => {
            emit("refreshData");
            emit("close");
        })
        .catch((error) => {
            console.log("error occurred");
        });
}

async function fetchData(id) {
    loading.value = true;
    await supplierStore.fetchSupplier(id);
    loading.value = false;
}

async function closeEditSupplierModal() {
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
                    <h5 class="modal-title">Edit Supplier</h5>
                    <button type="button" class="close">
                        <CrossSvgIcon @click="closeEditSupplierModal" />
                    </button>
                </div>

                <div class="modal-body">
                    <Loader v-if="loading" />
                    <div class="form-items" v-if="loading == false">
                        <form action="">
                            <div class="form-item mt-4">
                                <label class="my-2">Staus</label>
                                <p
                                    class="text-danger"
                                    v-if="
                                        supplierStore.edit_supplier_errors
                                            .status
                                    "
                                >
                                    {{
                                        supplierStore.edit_supplier_errors
                                            .status
                                    }}
                                </p>
                                <div class="d-flex">
                                    <span class="form-check">
                                        <input
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
                                <p
                                    class="text-danger"
                                    v-if="
                                        supplierStore.edit_supplier_errors.name
                                    "
                                >
                                    {{
                                        supplierStore.edit_supplier_errors.name
                                    }}
                                </p>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="supplier_data.name"
                                />
                            </div>
                            <div class="form-item">
                                <label class="my-2">Email</label>
                                <p
                                    class="text-danger"
                                    v-if="
                                        supplierStore.edit_supplier_errors.email
                                    "
                                >
                                    {{
                                        supplierStore.edit_supplier_errors.email
                                    }}
                                </p>
                                <input
                                    type="email"
                                    class="form-control"
                                    v-model="supplier_data.email"
                                />
                            </div>
                            <div class="form-item">
                                <label class="my-2">Phone</label>
                                <p
                                    class="text-danger"
                                    v-if="
                                        supplierStore.edit_supplier_errors.phone
                                    "
                                >
                                    {{
                                        supplierStore.edit_supplier_errors.phone
                                    }}
                                </p>
                                <input
                                    type="tel"
                                    class="form-control"
                                    v-model="supplier_data.phone"
                                />
                            </div>
                            <div class="form-item">
                                <label class="my-2">Tax Number</label>
                                <p
                                    class="text-danger"
                                    v-if="
                                        supplierStore.edit_supplier_errors
                                            .tax_number
                                    "
                                >
                                    {{
                                        supplierStore.edit_supplier_errors
                                            .tax_number
                                    }}
                                </p>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="supplier_data.tax_number"
                                />
                            </div>
                            <div class="form-item">
                                <label class="my-2">Country</label>
                                <p
                                    class="text-danger"
                                    v-if="
                                        supplierStore.edit_supplier_errors
                                            .country
                                    "
                                >
                                    {{
                                        supplierStore.edit_supplier_errors
                                            .country
                                    }}
                                </p>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="supplier_data.country"
                                />
                            </div>
                            <div class="form-item">
                                <label class="my-2">City</label>
                                <p
                                    class="text-danger"
                                    v-if="
                                        supplierStore.edit_supplier_errors.city
                                    "
                                >
                                    {{
                                        supplierStore.edit_supplier_errors.city
                                    }}
                                </p>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="supplier_data.city"
                                />
                            </div>
                            <div class="form-item">
                                <label class="my-2">Postal Code</label>
                                <p
                                    class="text-danger"
                                    v-if="
                                        supplierStore.edit_supplier_errors
                                            .postal_code
                                    "
                                >
                                    {{
                                        supplierStore.edit_supplier_errors
                                            .postal_code
                                    }}
                                </p>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="supplier_data.postal_code"
                                />
                            </div>
                            <div class="form-item mt-4">
                                <label class="my-2">Address</label>
                                <textarea
                                    v-model="supplier_data.address"
                                    class="form-control"
                                    rows="3"
                                ></textarea>
                            </div>
                            <div class="form-item mt-4">
                                <label class="my-2">Billing Address</label>
                                <textarea
                                    v-model="supplier_data.billing_address"
                                    class="form-control"
                                    rows="3"
                                ></textarea>
                            </div>
                            <div class="form-item mt-4">
                                <label class="my-2">Shipping Address</label>
                                <textarea
                                    v-model="supplier_data.shipping_address"
                                    class="form-control"
                                    rows="3"
                                ></textarea>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="modal-footer">
                    <button
                        class="btn btn-danger btn-sm"
                        @click="closeEditSupplierModal"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="btn btn-primary ml-1 btn-sm"
                        @click="submitData"
                    >
                        Save
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
