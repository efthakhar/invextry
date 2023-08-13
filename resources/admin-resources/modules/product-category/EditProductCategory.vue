<script setup>
import { ref, computed, onMounted } from "vue";
import CrossSvgIcon from "../../assets/icons/cross-svg-icon.vue";
import Loader from "../../components/shared/loader/Loader.vue";
import { useProductCategoryStore } from "./productCategoryStore";
import UploadContainer from "../../components/fileUploader/UploadContainer.vue";
import deleteUploadedFile from "../../utils/file";

const props = defineProps(["product_category_id"]);
const emit = defineEmits(["close", "refreshData"]);

const loading = ref(false);
const productCategoryStore = useProductCategoryStore();
const product_category_data = computed(
    () => productCategoryStore.current_product_category_item
);

let initial_files = [];
let items_to_delete = [];

async function submitData() {
    productCategoryStore
        .editProductCategory(
            JSON.parse(
                JSON.stringify(
                    productCategoryStore.current_product_category_item
                )
            )
        )
        .then(() => {
            items_to_delete.length > 0
                ? deleteUploadedFile(items_to_delete)
                : "";
            emit("refreshData");
            emit("close");
        })
        .catch((error) => {
            console.log("error occurred");
        });
}

async function fetchData(id) {
    loading.value = true;
    await productCategoryStore.fetchProductCategory(id);
    loading.value = false;
    initial_files = product_category_data.value.thumbnail;
}

async function closeEditProductCategoryModal() {
    if (product_category_data.value.thumbnail.length > 0) {
        product_category_data.value.thumbnail.forEach((element) => {
            if (!initial_files.includes(element)) {
                deleteUploadedFile(element.id);
            }
        });
    }
    productCategoryStore.resetCurrentProductCategoryData();
    emit("close");
}

onMounted(() => {
    fetchData(props.product_category_id);
});
</script>

<template>
    <div class="modal fade show d-block">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Product Category</h5>
                    <button type="button" class="close">
                        <CrossSvgIcon @click="closeEditProductCategoryModal" />
                    </button>
                </div>

                <div class="modal-body">
                    <Loader v-if="loading" />
                    <div class="form-items" v-if="loading == false">
                        <form action="">
                            <div class="form-item">
                                <label class="my-2"
                                    >Product Category Name</label
                                >
                                <p
                                    class="text-danger"
                                    v-if="
                                        productCategoryStore
                                            .edit_product_category_errors.name
                                    "
                                >
                                    {{
                                        productCategoryStore
                                            .edit_product_category_errors.name
                                    }}
                                </p>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="product_category_data.name"
                                />
                            </div>
                            <div class="form-item">
                                <label class="my-2"
                                    >Product Category Slug</label
                                >
                                <p
                                    class="text-danger"
                                    v-if="
                                        productCategoryStore
                                            .edit_product_category_errors.slug
                                    "
                                >
                                    {{
                                        productCategoryStore
                                            .edit_product_category_errors.slug
                                    }}
                                </p>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="product_category_data.slug"
                                />
                            </div>
                            <div class="form-item">
                                <UploadContainer
                                    @filesUploaded="
                                        (uploadedFiles) =>
                                            (product_category_data.thumbnail =
                                                uploadedFiles)
                                    "
                                    :uploads="product_category_data.thumbnail"
                                />
                            </div>
                        </form>
                    </div>
                </div>

                <div class="modal-footer">
                    <button
                        class="btn btn-danger btn-sm"
                        @click="closeEditProductCategoryModal"
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
