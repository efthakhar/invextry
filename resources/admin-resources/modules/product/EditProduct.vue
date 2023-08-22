<script setup>
import { ref, computed, onMounted } from "vue";
import CrossSvgIcon from "../../assets/icons/cross-svg-icon.vue";
import Loader from "../../components/shared/loader/Loader.vue";
import { useProductStore } from "./productStore";
import { useUnitStore } from "../unit/unitStore";
import UploadContainer from "../../components/fileUploader/UploadContainer.vue";
import deleteUploadedFile from "../../utils/file";

const props = defineProps([
    "product_id",
    "brands",
    "categories",
    "taxes",
    "base_units",
]);
const emit = defineEmits(["close", "refreshData"]);

const loading = ref(false);
const productStore = useProductStore();
const unitStore = useUnitStore();
const product_data = computed(() => productStore.current_product_item);

let initial_files = [];
let items_to_delete = [];

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
        .editProduct(
            JSON.parse(JSON.stringify(productStore.current_product_item))
        )
        .then(() => {
            items_to_delete.length > 0
                ? deleteUploadedFile(items_to_delete)
                : "";
            emit("refreshData");
            emit("close");
        })
        .catch((error) => {
            console.log("error occurred" + error);
        });
}

async function fetchData(id) {
    loading.value = true;
    await productStore.fetchProduct(id);
    unitStore
        .fetchHomogeneousUnits(productStore.current_product_item.unit_id)
        .then((response) => {
            homoGeneoursUnits.value = response;
        });
    loading.value = false;
    initial_files = product_data.value.gallery;
}

async function closeEditProductModal() {
    if (product_data.value.gallery.length > 0) {
        product_data.value.gallery.forEach((element) => {
            if (!initial_files.includes(element)) {
                deleteUploadedFile(element.id);
            }
        });
    }
    productStore.resetCurrentProductData();
    emit("close");
}

onMounted(async () => {
    fetchData(props.product_id);
});
</script>

<template>
    <div class="modal fade show d-block">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Product</h5>
                    <button type="button" class="close">
                        <CrossSvgIcon @click="closeEditProductModal" />
                    </button>
                </div>

                <div class="modal-body">
                    <Loader v-if="loading" />
                    <div class="form-items" v-if="loading == false">
                        <form action="">
                            <div class="form-item">
                                <label class="my-2">Product Name</label>
                                <p
                                    class="text-danger"
                                    v-if="productStore.edit_product_errors.name"
                                >
                                    {{ productStore.edit_product_errors.name }}
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
                                    v-if="productStore.edit_product_errors.slug"
                                >
                                    {{ productStore.edit_product_errors.slug }}
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
                                        v-if="
                                            productStore.edit_product_errors
                                                .code
                                        "
                                    >
                                        {{
                                            productStore.edit_product_errors
                                                .code
                                        }}
                                    </p>
                                    <input
                                        type="text"
                                        class="form-control"
                                        v-model="product_data.code"
                                    />
                                </div>
                                <div class="form-item col-sm-6">
                                    <label class="my-2"
                                        >Barcode Symbology</label
                                    >
                                    <p
                                        class="text-danger"
                                        v-if="
                                            productStore.edit_product_errors
                                                .barcode_symbology
                                        "
                                    >
                                        {{
                                            productStore.edit_product_errors
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
                                            productStore.edit_product_errors
                                                .category_id
                                        "
                                    >
                                        {{
                                            productStore.edit_product_errors
                                                .category_id
                                        }}
                                    </p>
                                    <select
                                        v-model="product_data.category_id"
                                        class="form-control"
                                    >
                                        <option value="">
                                            select category
                                        </option>
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
                                            productStore.edit_product_errors
                                                .brand_id
                                        "
                                    >
                                        {{
                                            productStore.edit_product_errors
                                                .brand_id
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
                                    <label class="my-2"
                                        >Stock Alert Quantity</label
                                    >
                                    <p
                                        class="text-danger"
                                        v-if="
                                            productStore.edit_product_errors
                                                .stock_alert_quantity
                                        "
                                    >
                                        {{
                                            productStore.edit_product_errors
                                                .stock_alert_quantity
                                        }}
                                    </p>
                                    <input
                                        class="form-control"
                                        type="number"
                                        v-model="
                                            product_data.stock_alert_quantity
                                        "
                                    />
                                </div>
                                <!-- product unit -->
                                <div class="form-item col-sm-6">
                                    <label class="my-2">Product Unit</label>
                                    <p
                                        class="text-danger"
                                        v-if="
                                            productStore.edit_product_errors
                                                .unit_id
                                        "
                                    >
                                        {{
                                            productStore.edit_product_errors
                                                .unit_id
                                        }}
                                    </p>
                                    <select
                                        @change="
                                            onUnitChange(product_data.unit_id)
                                        "
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
                                            productStore.edit_product_errors
                                                .purchase_unit_id
                                        "
                                    >
                                        {{
                                            productStore.edit_product_errors
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
                                            productStore.edit_product_errors
                                                .sale_unit_id
                                        "
                                    >
                                        {{
                                            productStore.edit_product_errors
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
                                            productStore.edit_product_errors
                                                .purchase_price
                                        "
                                    >
                                        {{
                                            productStore.edit_product_errors
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
                                            productStore.edit_product_errors
                                                .sale_price
                                        "
                                    >
                                        {{
                                            productStore.edit_product_errors
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
                                            productStore.edit_product_errors
                                                .tax_id
                                        "
                                    >
                                        {{
                                            productStore.edit_product_errors
                                                .tax_id
                                        }}
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
                                            productStore.edit_product_errors
                                                .tax_type
                                        "
                                    >
                                        {{
                                            productStore.edit_product_errors
                                                .tax_type
                                        }}
                                    </p>
                                    <select
                                        v-model="product_data.tax_type"
                                        class="form-control"
                                    >
                                        <option value="inclusive">
                                            inclusive
                                        </option>
                                        <option value="exclusive">
                                            exclusive
                                        </option>
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
                                            (product_data.gallery =
                                                uploadedFiles)
                                    "
                                    @filesDeleted="
                                        (deletedFile) =>
                                            items_to_delete.push(deletedFile)
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
                </div>

                <div class="modal-footer">
                    <button
                        class="btn btn-danger btn-sm"
                        @click="closeEditProductModal"
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
