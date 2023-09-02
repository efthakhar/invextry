<script setup>
import axios from "axios";
import { onMounted, ref } from "vue";
import crossSvgIcon from "../../assets/icons/cross-svg-icon.vue";
const emit = defineEmits(["filesUploaded", "filesDeleted"]);
const props = defineProps({
    multiple: Boolean,
    uploads: Array,
});
const uploaded_files = ref([]);

function uploadFiles(event) {
    // if multiple false then remove the previously uploaded file
    if (props.multiple == false && uploaded_files.value.length > 0) {
        let previously_uploaded_ids = uploaded_files.value.map(
            (jsonObj) => jsonObj["id"]
        );
        deleteFile(previously_uploaded_ids);
    }

    const files = event.target.files;
    const formData = new FormData();

    for (let i = 0; i < files.length; i++) {
        formData.append("files[]", files[i]);
    }

    axios
        .post("/api/upload", formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        })
        .then((response) => {
            uploaded_files.value = uploaded_files.value.concat(
                response.data.files
            );

            emit("filesUploaded", uploaded_files.value);
        })
        .catch((error) => {
            console.error(error);
        });
}

async function deleteFile(id) {
    if (Array.isArray(id)) {
        uploaded_files.value = uploaded_files.value.filter(
            (item) => !id.includes(item.id)
        );
        id.forEach((i) => emit("filesDeleted", i));
    } else {
        uploaded_files.value = uploaded_files.value.filter(
            (item) => item.id !== id
        );
        emit("filesDeleted", id);
    }

    emit("filesUploaded", uploaded_files.value);
}

onMounted(() => {
    uploaded_files.value = props.uploads ?? [];
});
</script>

<template>
    <div class="form-file py-3">
        <div class="input-container">
            <input
                type="file"
                class="form-file-input"
                id="file"
                name="file[]"
                multiple="true"
                v-if="multiple == true"
                @change="uploadFiles"
            />
            <input
                type="file"
                class="form-file-input"
                id="file"
                name="file"
                v-if="multiple == false"
                @change="uploadFiles"
            />
        </div>
        <div class="uploaded-images d-flex flex-wrap mt-2">
            <div class="my-1 mx-1 uploaded_item" v-for="file in uploaded_files" :key="file.id">
                <crossSvgIcon
                    width="18px"
                    height="18px"
                    fill="red"
                    @click="deleteFile(file.id)"
                />
                <img :src="file.url" alt="" :id="file.id" />
            </div>
        </div>
    </div>
</template>

<style>
.uploaded-images .uploaded_item {
    box-shadow: 1px 1px 14px rgba(128, 128, 128, 0.479);
    position: relative;
}
.uploaded_item img {
    width: 78px;
    height: 78px;
}
.uploaded_item svg {
    background-color: rgb(255, 255, 255);
    box-shadow: 1px 1px 14px grey;
    position: absolute;
    right: 0px;
    top: 0px;
    border-radius: 50%;
}
</style>
