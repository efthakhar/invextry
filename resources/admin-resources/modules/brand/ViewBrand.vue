<script setup>
import { ref, computed, onMounted } from "vue";
import CrossSvgIcon from "../../assets/icons/cross-svg-icon.vue";
import ImagesBox from "../../components/img/ImagesBox.vue"
import Loader from "../../components/shared/loader/Loader.vue";
import { useBrandStore } from "./brandStore";

const props = defineProps(["brand_id"]);
const emit = defineEmits(["close"]);

const loading = ref(false);
const brandStore = useBrandStore();
const brand_data = computed(() => brandStore.current_brand_item);

async function fetchData(id) {
    loading.value = true;
    await brandStore.fetchBrand(id);
    loading.value = false;
}

async function closeViewBrandModal() {
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
                    <h5 class="modal-title">Brand Details</h5>
                    <button type="button" class="close">
                        <CrossSvgIcon @click="closeViewBrandModal" />
                    </button>
                </div>

                <div class="modal-body">
                    <Loader v-if="loading" />
                    <div class="form-items" v-if="loading == false">
                        <form action="">
                        <div class="form-item">
                            <label class="my-2">Brand Name</label>
                            <input
                                disabled
                                type="text"
                                class="form-control"
                                v-model="brand_data.name"
                            />
                        </div>
                        <div class="form-item">
                            <label class="my-2">Brand Slug</label>
                            <input
                                disabled
                                type="text"
                                class="form-control"
                                v-model="brand_data.slug"
                            />
                        </div>
                        <div class="form-item">
                            <label class="my-2">Brand Logo</label>
                            <ImagesBox :images="brand_data.logo" />
                        </div>
                    </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>