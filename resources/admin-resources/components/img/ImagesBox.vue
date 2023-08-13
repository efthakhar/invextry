<script setup>
const props = defineProps({
    images: Array,
});

</script>

<template>
   
<div class="d-flex flex-wrap mt-2 imagesBox">
    <div class="my-1 mx-1 imagesBoxItem" v-for="file in images">
        <img :src="file.url" alt="" :id="file.id" />
    </div>
</div>

</template>

<style>
.imagesBox .imagesBoxItem {
    border: .3px solid rgba(128, 128, 128, 0.247);
}
.imagesBoxItem img {
    width: 78px;
    height: 78px;
}

</style>
