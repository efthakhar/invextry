<script setup>
import { computed, onMounted, ref } from "vue";
import CrossSvgIcon from "../../assets/icons/cross-svg-icon.vue";
import { useProductStore } from "./productStore";
import { useUnitStore } from "../unit/unitStore";
import UploadContainer from "../../components/fileUploader/UploadContainer.vue";
import deleteUploadedFile from "../../utils/file";

const props = defineProps(["brands", "categories", "taxes", "base_units"]);
const emit = defineEmits(["close", "refreshData"]);

const productStore = useProductStore();
const unitStore = useUnitStore();
const product_data = computed(() => productStore.current_product_item);

const homoGeneoursUnits = ref([]);

async function onUnitChange(id) {
    product_data.value.sale_unit_id = "";
    product_data.value.purchase_unit_id = "";
    unitStore.fetchHomogeneousUnits(id).then((response) => {
        homoGeneoursUnits.value = response;
    });
}

async function submitData() {
    productStore
        .addProduct(
            JSON.parse(JSON.stringify(productStore.current_product_item))
        )
        .then(() => {
            emit("refreshData");
            emit("close");
        })
        .catch((error) => {
            console.log("error occurred" + error);
        });
}

async function closeAddProductModal() {
    if (product_data.value.gallery.length > 0) {
        let fileIDsToDelete = product_data.value.gallery.map(
            (jsonObj) => jsonObj["id"]
        );
        deleteUploadedFile(fileIDsToDelete);
    }

    productStore.resetCurrentProductData();
    emit("close");
}

onMounted(() => {
    productStore.resetCurrentProductData();
});
</script>

<template>
    <div class="modal fade show d-block">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Product</h5>
                    <button type="button" class="close">
                        <CrossSvgIcon @click="closeAddProductModal" />
                    </button>
                </div>

                <div class="modal-body">
                    <form action="">
                        <div class="form-item">
                            <label class="my-2">Product Name</label>
                            <p
                                class="text-danger"
                                v-if="productStore.add_product_errors.name"
                            >
                                {{ productStore.add_product_errors.name }}
                            </p>
                            <input
                                type="text"
                                class="form-control"
                                v-model="product_data.name"
                            />
                        </div>
                        <div class="form-item">
                            <label class="my-2">Product Slug</label>
                            <p
                                class="text-danger"
                                v-if="productStore.add_product_errors.slug"
                            >
                                {{ productStore.add_product_errors.slug }}
                            </p>
                            <input
                                type="text"
                                class="form-control"
                                v-model="product_data.slug"
                            />
                        </div>
                        <div class="row">
                            <div class="form-item col-sm-6">
                                <label class="my-2">Code</label>
                                <p
                                    class="text-danger"
                                    v-if="productStore.add_product_errors.code"
                                >
                                    {{ productStore.add_product_errors.code }}
                                </p>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="product_data.code"
                                />
                            </div>
                            <div class="form-item col-sm-6">
                                <label class="my-2">Barcode Symbology</label>
                                <p
                                    class="text-danger"
                                    v-if="
                                        productStore.add_product_errors
                                            .barcode_symbology
                                    "
                                >
                                    {{
                                        productStore.add_product_errors
                                            .barcode_symbology
                                    }}
                                </p>
                                <select
                                    v-model="product_data.barcode_symbology"
                                    class="form-control"
                                >
                                    <option selected value="CODE128">
                                        CODE128
                                    </option>
                                    <option value="CODE39">CODE39</option>
                                    <option value="EAN8">EAN8</option>
                                    <option value="EAN13">EAN13</option>
                                    <option value="UPC">UPC</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-item col-sm-6">
                                <label class="my-2">Category</label>
                                <p
                                    class="text-danger"
                                    v-if="
                                        productStore.add_product_errors
                                            .category_id
                                    "
                                >
                                    {{
                                        productStore.add_product_errors
                                            .category_id
                                    }}
                                </p>
                                <select
                                    v-model="product_data.category_id"
                                    class="form-control"
                                >
                                    <option value="">select category</option>
                                    <option
                                        :value="category.id"
                                        v-for="category in categories"
                                    >
                                        {{ category.name }}
                                    </option>
                                </select>
                            </div>
                            <div class="form-item col-sm-6">
                                <label class="my-2">Brand</label>
                                <p
                                    class="text-danger"
                                    v-if="
                                        productStore.add_product_errors.brand_id
                                    "
                                >
                                    {{
                                        productStore.add_product_errors.brand_id
                                    }}
                                </p>
                                <select
                                    v-model="product_data.brand_id"
                                    class="form-control"
                                >
                                    <option value="">select brand</option>
                                    <option
                                        :value="brand.id"
                                        v-for="brand in brands"
                                    >
                                        {{ brand.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <!-- stock alert quantity -->
                            <div class="form-item col-sm-6">
                                <label class="my-2">Stock Alert Quantity</label>
                                <p
                                    class="text-danger"
                                    v-if="
                                        productStore.add_product_errors
                                            .stock_alert_quantity
                                    "
                                >
                                    {{
                                        productStore.add_product_errors
                                            .stock_alert_quantity
                                    }}
                                </p>
                                <input
                                    class="form-control"
                                    type="number"
                                    v-model="product_data.stock_alert_quantity"
                                />
                            </div>
                            <!-- product unit -->
                            <div class="form-item col-sm-6">
                                <label class="my-2">Product Unit</label>
                                <p
                                    class="text-danger"
                                    v-if="
                                        productStore.add_product_errors.unit_id
                                    "
                                >
                                    {{
                                        productStore.add_product_errors.unit_id
                                    }}
                                </p>
                                <select
                                    @change="onUnitChange(product_data.unit_id)"
                                    v-model="product_data.unit_id"
                                    class="form-control"
                                >
                                    <option value="">select unit</option>
                                    <option
                                        :value="unit.id"
                                        v-for="unit in base_units"
                                    >
                                        {{ unit.name }}
                                    </option>
                                </select>
                            </div>
                            <!-- purchase unit -->
                            <!-- <div
                                class="form-item col-sm-6"
                                :class="{ 'd-none': !product_data.unit_id }"
                            >
                                <label class="my-2">Purchase Unit</label>
                                <p
                                    class="text-danger"
                                    v-if="
                                        productStore.add_product_errors
                                            .purchase_unit_id
                                    "
                                >
                                    {{
                                        productStore.add_product_errors
                                            .purchase_unit_id
                                    }}
                                </p>
                                <select
                                    :disabled="!product_data.unit_id"
                                    v-model="product_data.purchase_unit_id"
                                    class="form-control"
                                >
                                    <option value="">select unit</option>
                                    <option
                                        :value="unit.id"
                                        v-for="unit in homoGeneoursUnits"
                                    >
                                        {{ unit.name }}
                                    </option>
                                </select>
                            </div> -->
                            <!-- sale unit -->
                            <!-- <div
                                class="form-item col-sm-6"
                                :class="{ 'd-none': !product_data.unit_id }"
                            >
                                <label class="my-2">Sale Unit</label>
                                <p
                                    class="text-danger"
                                    v-if="
                                        productStore.add_product_errors
                                            .sale_unit_id
                                    "
                                >
                                    {{
                                        productStore.add_product_errors
                                            .sale_unit_id
                                    }}
                                </p>
                                <select
                                    :disabled="!product_data.unit_id"
                                    v-model="product_data.sale_unit_id"
                                    class="form-control"
                                >
                                    <option value="">select unit</option>
                                    <option
                                        :value="unit.id"
                                        v-for="unit in homoGeneoursUnits"
                                    >
                                        {{ unit.name }}
                                    </option>
                                </select>
                            </div> -->
                            <!-- purchase price -->
                            <div class="form-item col-sm-6">
                                <label class="my-2">Purchase Price</label>
                                <p
                                    class="text-danger"
                                    v-if="
                                        productStore.add_product_errors
                                            .purchase_price
                                    "
                                >
                                    {{
                                        productStore.add_product_errors
                                            .purchase_price
                                    }}
                                </p>
                                <input
                                    class="form-control"
                                    type="number"
                                    v-model="product_data.purchase_price"
                                />
                            </div>
                            <!-- sale price -->
                            <div class="form-item col-sm-6">
                                <label class="my-2">Sale Price</label>
                                <p
                                    class="text-danger"
                                    v-if="
                                        productStore.add_product_errors
                                            .sale_price
                                    "
                                >
                                    {{
                                        productStore.add_product_errors
                                            .sale_price
                                    }}
                                </p>
                                <input
                                    class="form-control"
                                    type="number"
                                    v-model="product_data.sale_price"
                                />
                            </div>
                            <!-- tax -->
                            <div class="form-item col-sm-6">
                                <label class="my-2">Tax</label>
                                <p
                                    class="text-danger"
                                    v-if="
                                        productStore.add_product_errors.tax_id
                                    "
                                >
                                    {{ productStore.add_product_errors.tax_id }}
                                </p>
                                <select
                                    v-model="product_data.tax_id"
                                    class="form-control"
                                >
                                    <option value="">select tax</option>
                                    <option
                                        :value="tax.id"
                                        v-for="tax in taxes"
                                    >
                                        {{ tax.name }}
                                    </option>
                                </select>
                            </div>
                            <!-- tax type-->
                            <div class="form-item col-sm-6">
                                <label class="my-2">Tax Type</label>
                                <p
                                    class="text-danger"
                                    v-if="
                                        productStore.add_product_errors.tax_type
                                    "
                                >
                                    {{
                                        productStore.add_product_errors.tax_type
                                    }}
                                </p>
                                <select
                                    v-model="product_data.tax_type"
                                    class="form-control"
                                >
                                    <option value="inclusive">inclusive</option>
                                    <option value="exclusive">exclusive</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-item mt-4">
                            <label class="my-2"
                                >Product Images (multiple allowed)</label
                            >
                            <UploadContainer
                                multiple
                                @filesUploaded="
                                    (uploadedFiles) =>
                                        (product_data.gallery = uploadedFiles)
                                "
                                :uploads="product_data.gallery"
                            />
                        </div>

                        <div class="form-item mt-4">
                            <label class="my-2">Description</label>
                            <textarea
                                v-model="product_data.description"
                                class="form-control"
                                rows="5"
                            ></textarea>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button
                        class="btn btn-danger btn-sm"
                        @click="closeAddProductModal"
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
