<script setup>
import { ref, computed, onMounted } from "vue";
import CrossSvgIcon from "../../assets/icons/cross-svg-icon.vue";
import ImagesBox from "../../components/img/ImagesBox.vue"
import Loader from "../../components/shared/loader/Loader.vue";
import { useProductCategoryStore } from "./productCategoryStore";

const props = defineProps(["product_category_id"]);
const emit = defineEmits(["close"]);

const loading = ref(false);
const productCategoryStore = useProductCategoryStore();
const product_category_data = computed(() => productCategoryStore.current_product_category_item);

async function fetchData(id) {
    loading.value = true;
    await productCategoryStore.fetchProductCategory(id);
    loading.value = false;
}

async function closeViewProductCategoryModal() {
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
                    <h5 class="modal-title">Product Category Details</h5>
                    <button type="button" class="close">
                        <CrossSvgIcon @click="closeViewProductCategoryModal" />
                    </button>
                </div>

                <div class="modal-body">
                    <Loader v-if="loading" />
                    <div class="form-items" v-if="loading == false">
                        <form action="">
                        <div class="form-item">
                            <label class="my-2">Category Name</label>
                            <input
                                disabled
                                type="text"
                                class="form-control"
                                v-model="product_category_data.name"
                            />
                        </div>
                        <div class="form-item">
                            <label class="my-2">Category Slug</label>
                            <input
                                disabled
                                type="text"
                                class="form-control"
                                v-model="product_category_data.slug"
                            />
                        </div>
                        <div class="form-item">
                            <label class="my-2">Category Thumbnail</label>
                            <ImagesBox :images="product_category_data.thumbnail" />
                        </div>
                    </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>