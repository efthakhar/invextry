<script setup>
import { ref, computed, onMounted } from "vue";
import CrossSvgIcon from "../../assets/icons/cross-svg-icon.vue";
import Loader from "../../components/shared/loader/Loader.vue";
import { useCustomerStore } from "./customerStore";

const props = defineProps(["customer_id"]);
const emit = defineEmits(["close", "refreshData"]);

const loading = ref(false);
const customerStore = useCustomerStore();
const customer_data = computed(() => customerStore.current_customer_item);

async function submitData() {
    customerStore
        .editCustomer(
            JSON.parse(JSON.stringify(customerStore.current_customer_item))
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
    await customerStore.fetchCustomer(id);
    loading.value = false;
}

async function closeEditCustomerModal() {
    customerStore.resetCurrentCustomerData();
    emit("close");
}

onMounted(() => {
    fetchData(props.customer_id);
});
</script>

<template>
    <div class="modal fade show d-block">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Customer</h5>
                    <button type="button" class="close">
                        <CrossSvgIcon @click="closeEditCustomerModal" />
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
                                        customerStore.edit_customer_errors
                                            .status
                                    "
                                >
                                    {{
                                        customerStore.edit_customer_errors
                                            .status
                                    }}
                                </p>
                                <div class="d-flex">
                                    <span class="form-check">
                                        <input
                                            class="form-check-input"
                                            type="radio"
                                            v-model="customer_data.status"
                                            id="active"
                                            value="active"
                                            :checked="
                                                customer_data.status == 'active'
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
                                            v-model="customer_data.status"
                                            id="disabled"
                                            value="disabled"
                                            :checked="
                                                customer_data.status ==
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
                                        customerStore.edit_customer_errors.name
                                    "
                                >
                                    {{
                                        customerStore.edit_customer_errors.name
                                    }}
                                </p>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="customer_data.name"
                                />
                            </div>
                            <div class="form-item">
                                <label class="my-2">Email</label>
                                <p
                                    class="text-danger"
                                    v-if="
                                        customerStore.edit_customer_errors.email
                                    "
                                >
                                    {{
                                        customerStore.edit_customer_errors.email
                                    }}
                                </p>
                                <input
                                    type="email"
                                    class="form-control"
                                    v-model="customer_data.email"
                                />
                            </div>
                            <div class="form-item">
                                <label class="my-2">Phone</label>
                                <p
                                    class="text-danger"
                                    v-if="
                                        customerStore.edit_customer_errors.phone
                                    "
                                >
                                    {{
                                        customerStore.edit_customer_errors.phone
                                    }}
                                </p>
                                <input
                                    type="tel"
                                    class="form-control"
                                    v-model="customer_data.phone"
                                />
                            </div>
                            <div class="form-item">
                                <label class="my-2">Tax Number</label>
                                <p
                                    class="text-danger"
                                    v-if="
                                        customerStore.edit_customer_errors
                                            .tax_number
                                    "
                                >
                                    {{
                                        customerStore.edit_customer_errors
                                            .tax_number
                                    }}
                                </p>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="customer_data.tax_number"
                                />
                            </div>
                            <div class="form-item">
                                <label class="my-2">Country</label>
                                <p
                                    class="text-danger"
                                    v-if="
                                        customerStore.edit_customer_errors
                                            .country
                                    "
                                >
                                    {{
                                        customerStore.edit_customer_errors
                                            .country
                                    }}
                                </p>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="customer_data.country"
                                />
                            </div>
                            <div class="form-item">
                                <label class="my-2">City</label>
                                <p
                                    class="text-danger"
                                    v-if="
                                        customerStore.edit_customer_errors.city
                                    "
                                >
                                    {{
                                        customerStore.edit_customer_errors.city
                                    }}
                                </p>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="customer_data.city"
                                />
                            </div>
                            <div class="form-item">
                                <label class="my-2">Postal Code</label>
                                <p
                                    class="text-danger"
                                    v-if="
                                        customerStore.edit_customer_errors
                                            .postal_code
                                    "
                                >
                                    {{
                                        customerStore.edit_customer_errors
                                            .postal_code
                                    }}
                                </p>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="customer_data.postal_code"
                                />
                            </div>
                            <div class="form-item mt-4">
                                <label class="my-2">Address</label>
                                <textarea
                                    v-model="customer_data.address"
                                    class="form-control"
                                    rows="3"
                                ></textarea>
                            </div>
                            <div class="form-item mt-4">
                                <label class="my-2">Billing Address</label>
                                <textarea
                                    v-model="customer_data.billing_address"
                                    class="form-control"
                                    rows="3"
                                ></textarea>
                            </div>
                            <div class="form-item mt-4">
                                <label class="my-2">Shipping Address</label>
                                <textarea
                                    v-model="customer_data.shipping_address"
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
                        @click="closeEditCustomerModal"
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
