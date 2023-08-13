<script setup>
import { computed, onMounted } from "vue";
import CrossSvgIcon from "../../assets/icons/cross-svg-icon.vue";
import { useProductCategoryStore } from "./productCategoryStore";
import UploadContainer from "../../components/fileUploader/UploadContainer.vue";
import deleteUploadedFile from "../../utils/file";

const emit = defineEmits(["close", "refreshData"]);

const productCategoryStore = useProductCategoryStore();
const product_category_data = computed(
    () => productCategoryStore.current_product_category_item
);

async function submitData() {
    productCategoryStore
        .addProductCategory(
            JSON.parse(
                JSON.stringify(
                    productCategoryStore.current_product_category_item
                )
            )
        )
        .then(() => {
            emit("refreshData");
            emit("close");
        })
        .catch((error) => {
            console.log("error occurred");
        });
}

async function closeAddProductCategoryModal() {

    if (product_category_data.value.thumbnail.length > 0) {
        let fileIDsToDelete = product_category_data.value.thumbnail.map(
            (jsonObj) => jsonObj["id"]
        );
        deleteUploadedFile(fileIDsToDelete);
    }

    productCategoryStore.resetCurrentProductCategoryData();
    emit("close");
}

onMounted(() => {
    productCategoryStore.resetCurrentProductCategoryData();
});
</script>

<template>
    <div class="modal fade show d-block">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Product Category</h5>
                    <button type="button" class="close">
                        <CrossSvgIcon @click="closeAddProductCategoryModal" />
                    </button>
                </div>

                <div class="modal-body">
                    <form action="">
                        <div class="form-item">
                            <label class="my-2">Category Name</label>
                            <p
                                class="text-danger"
                                v-if="
                                    productCategoryStore
                                        .add_product_category_errors.name
                                "
                            >
                                {{
                                    productCategoryStore
                                        .add_product_category_errors.name
                                }}
                            </p>
                            <input
                                type="text"
                                class="form-control"
                                v-model="product_category_data.name"
                            />
                        </div>
                        <div class="form-item">
                            <label class="my-2">Category Slug</label>
                            <p
                                class="text-danger"
                                v-if="
                                    productCategoryStore
                                        .add_product_category_errors.slug
                                "
                            >
                                {{
                                    productCategoryStore
                                        .add_product_category_errors.slug
                                }}
                            </p>
                            <input
                                type="text"
                                class="form-control"
                                v-model="product_category_data.slug"
                            />
                        </div>
                        <div class="form-item">
                            <label class="my-2">Category Thumbnail</label>
                            <UploadContainer
                                @filesUploaded="
                                    (uploadedFiles) =>
                                        (product_category_data.thumbnail =
                                            uploadedFiles)
                                "
                            />
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button
                        class="btn btn-danger btn-sm"
                        @click="closeAddProductCategoryModal"
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
