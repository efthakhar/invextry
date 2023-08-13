<script setup>
import { ref, computed, onMounted } from "vue";
import CrossSvgIcon from "../../assets/icons/cross-svg-icon.vue";
import Loader from "../../components/shared/loader/Loader.vue";
import { useBrandStore } from "./brandStore";
import UploadContainer from "../../components/fileUploader/UploadContainer.vue";
import deleteUploadedFile from "../../utils/file";

const props = defineProps(["brand_id"]);
const emit = defineEmits(["close", "refreshData"]);

const loading = ref(false);
const brandStore = useBrandStore();
const brand_data = computed(() => brandStore.current_brand_item);

let initial_files = [];
let items_to_delete = [];

async function submitData() {
    brandStore
        .editBrand(JSON.parse(JSON.stringify(brandStore.current_brand_item)))
        .then(() => {
            // deleting removed if brand  saved
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
    await brandStore.fetchBrand(id);
    loading.value = false;
    initial_files = brand_data.value.logo;
}

async function closeEditBrandModal() {
    // deleting uploaded files if brand not saved
    if (brand_data.value.logo.length > 0) {
        brand_data.value.logo.forEach((element) => {
            if (!initial_files.includes(element)) {
                deleteUploadedFile(element.id);
            }
        });
    }

    brandStore.resetCurrentBrandData();
    emit("close");
}

onMounted(() => {
    fetchData(props.brand_id);
});
</script>

<template>
    <div class="modal fade show d-block">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Brand</h5>
                    <button type="button" class="close">
                        <CrossSvgIcon @click="closeEditBrandModal" />
                    </button>
                </div>

                <div class="modal-body">
                    <Loader v-if="loading" />
                    <div class="form-items" v-if="loading == false">
                        <form action="">
                            <div class="form-item">
                                <label class="my-2">Brand Name</label>
                                <p
                                    class="text-danger"
                                    v-if="brandStore.edit_brand_errors.name"
                                >
                                    {{ brandStore.edit_brand_errors.name }}
                                </p>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="brand_data.name"
                                />
                            </div>
                            <div class="form-item">
                                <label class="my-2">Brand Slug</label>
                                <p
                                    class="text-danger"
                                    v-if="brandStore.edit_brand_errors.slug"
                                >
                                    {{ brandStore.edit_brand_errors.slug }}
                                </p>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="brand_data.slug"
                                />
                            </div>
                            <div class="form-item">
                                <UploadContainer
                                    @filesUploaded="
                                        (uploadedFiles) =>
                                            (brand_data.logo = uploadedFiles)
                                    "
                                    :uploads="brand_data.logo"
                                    @filesDeleted="
                                        (deletedFile) =>
                                            items_to_delete.push(deletedFile)
                                    "
                                />
                            </div>
                        </form>
                    </div>
                </div>

                <div class="modal-footer">
                    <button
                        class="btn btn-danger btn-sm"
                        @click="closeEditBrandModal"
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
