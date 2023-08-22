<script setup>
import { ref, computed, onMounted } from "vue";
import CrossSvgIcon from "../../assets/icons/cross-svg-icon.vue";
import ImagesBox from "../../components/img/ImagesBox.vue";
import Loader from "../../components/shared/loader/Loader.vue";
import { useProductStore } from "./productStore";

const props = defineProps(["product_id"]);
const emit = defineEmits(["close"]);

const loading = ref(false);
const productStore = useProductStore();
const product_data = computed(() => productStore.current_product_item);

async function fetchData(id) {
    loading.value = true;
    await productStore.fetchProduct(id);
    loading.value = false;
}

async function closeViewProductModal() {
    productStore.resetCurrentProductData();
    emit("close");
}

onMounted(() => {
    fetchData(props.product_id);
});
</script>

<template>
    <div class="modal fade show d-block">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Product Details</h5>
                    <button type="button" class="close">
                        <CrossSvgIcon @click="closeViewProductModal" />
                    </button>
                </div>

                <div class="modal-body">
                    <Loader v-if="loading" />
                    <div class="form-items" v-if="loading == false">
                        <form action="">
                            <div class="form-item">
                                <label class="my-2">Product Name</label>
                                <input
                                    disabled
                                    type="text"
                                    class="form-control"
                                    :value="product_data.name"
                                />
                            </div>
                            <div class="form-item">
                                <label class="my-2">Product Slug</label>
                                <input
                                    disabled
                                    type="text"
                                    class="form-control"
                                    :value="product_data.slug"
                                />
                            </div>
                            <div class="row">
                                <div class="form-item col-sm-6">
                                    <label class="my-2">Code</label>
                                    <input
                                        disabled
                                        type="text"
                                        class="form-control"
                                        :value="product_data.code"
                                    />
                                </div>
                                <div class="form-item col-sm-6">
                                    <label class="my-2"
                                        >Barcode Symbology</label
                                    >
                                    <input
                                        disabled
                                        type="text"
                                        class="form-control"
                                        :value="product_data.barcode_symbology"
                                    />
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-item col-sm-6">
                                    <label class="my-2">Category</label>
                                    <input
                                        disabled
                                        type="text"
                                        class="form-control"
                                        :value="product_data.category.name"
                                    />
                                </div>
                                <div class="form-item col-sm-6">
                                    <label class="my-2">Brand</label>
                                    <input
                                        disabled
                                        type="text"
                                        class="form-control"
                                        :value="product_data.brand.name"
                                    />
                                </div>
                            </div>
                            <div class="row">
                                <!-- stock alert quantity -->
                                <div class="form-item col-sm-6">
                                    <label class="my-2"
                                        >Stock Alert Quantity</label
                                    >
                                    <input
                                        disabled
                                        type="number"
                                        class="form-control"
                                        :value="
                                            product_data.stock_alert_quantity
                                        "
                                    />
                                </div>
                                <!-- product unit -->
                                <div class="form-item col-sm-6">
                                    <label class="my-2">Product Unit</label>
                                    <input
                                        disabled
                                        type="text"
                                        class="form-control"
                                        :value="product_data.unit.name"
                                    /> 
                                </div>
                                <!-- product Sale unit -->
                                <!-- <div class="form-item col-sm-6">
                                    <label class="my-2">Purchase Unit</label>
                                    <input
                                        disabled
                                        type="text"
                                        class="form-control"
                                        :value="product_data.purchase_unit.name"
                                    />
                                </div> -->
                                <!-- product Sale unit -->
                                <!-- <div class="form-item col-sm-6">
                                    <label class="my-2">Sale Unit</label>
                                    <input
                                        disabled
                                        type="text"
                                        class="form-control"
                                        :value="product_data.sale_unit.name"
                                    />
                                </div> -->

                                <!-- purchase price -->
                                <div class="form-item col-sm-6">
                                    <label class="my-2">Purchase Price</label>
                                    <input
                                        disabled
                                        class="form-control"
                                        type="number"
                                        :value="product_data.purchase_price"
                                    />
                                </div>
                                <!-- sale price -->
                                <div class="form-item col-sm-6">
                                    <label class="my-2">Sale Price</label>
                                    <input
                                        disabled
                                        class="form-control"
                                        type="number"
                                        :value="product_data.sale_price"
                                    />
                                </div>
                                <!-- tax -->
                                <div class="form-item col-sm-6">
                                    <label class="my-2">Tax</label>
                                    <input
                                        disabled
                                        class="form-control"
                                        type="text"
                                        :value="product_data.tax?product_data.tax.name:''"
                                    />
                                </div>
                                <!-- tax type-->
                                <div class="form-item col-sm-6">
                                    <label class="my-2">Tax Type</label>
                                    <input
                                        disabled
                                        class="form-control"
                                        type="text"
                                        :value="product_data.tax_type"
                                    />
                                </div>
                            </div>

                            <div class="form-item mt-4">
                                <label class="my-2">Product Images</label>
                                <ImagesBox :images="product_data.gallery" />
                            </div>

                            <div class="form-item mt-4">
                                <label class="my-2">Description</label>
                                <textarea
                                    disabled
                                    :value="product_data.description"
                                    class="form-control"
                                    rows="5"
                                ></textarea>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
