<script setup>
import Sidebar from "../components/Sidebar.vue";
import Navbar from "../components/Navbar.vue";
import Loader from "../components/shared/loader/Loader.vue";
import NoticationsContainer from "../components/shared/notification/notications-container.vue";
import ConfirmBox from "../components/shared/confirm-alert/confirm-box.vue";
import { useConfirmStore } from "../components/shared/confirm-alert/confirmStore";
import { onMounted, ref } from "vue";
import { useAuthStore } from "../stores/authStore";

const adminReady = ref(false);
const authStore = useAuthStore();
const confirmStore = useConfirmStore();

onMounted(async () => {
    adminReady.value = false;

    await authStore.getAuthUser();

    if (!authStore.permissions.includes("manage_dashboard")) {
        window.location.href = "/";
    } else {
        adminReady.value = true;
    }
});
</script>
<template>
    <div>
        <div v-if="adminReady == false" class="pt-5">
            <Loader />
        </div>
        <div
            id="app"
            v-if="
                adminReady == true &&
                authStore.permissions.includes('manage_dashboard')
            "
        >
            <Sidebar />
            <div id="main">
                <Navbar />
                <div class="main-content container-fluid">
                    <router-view />
                </div>
            </div>
        </div>
        <div class="admin-area-modals-container">
            <ConfirmBox v-if="confirmStore.show_confirm_box" />
            <NoticationsContainer />
        </div>
    </div>
</template>
