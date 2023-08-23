<script setup>
import menuSvgIcon from "../assets/icons/menu-svg-icon.vue";
import userSvgIcon from "../assets/icons/user-svg-icon.vue";
import logoutSvgIcon from "../assets/icons/logout-svg-icon.vue";
import settingSvgIcon from "../assets/icons/setting-svg-icon.vue";
import { useSidebar } from "../stores/sidebar";
import { ref } from "vue";
const sidebarStore = useSidebar();
const userDropDown = ref(false);
</script>

<template>
    <nav class="navbar navbar-header navbar-expand navbar-light">
        <menuSvgIcon @click="sidebarStore.toggle()" />
        <ul class="navbar-nav d-flex align-items-center navbar-light ms-auto">
            <div class="top-nav-item position-relative">
                <span @click="userDropDown = !userDropDown">
                    <userSvgIcon width="25px" height="25px" />
                </span>
                <div v-if="userDropDown" class="top-nav-dropdown">
                    <a class="top-nav-dropdown-item" href="/logout">
                        <logoutSvgIcon
                            width="16px"
                            height="16px"
                            color="currentColor"
                        />
                        <span class="ms-2">Logout </span>
                    </a>
                    <!-- <a class="top-nav-dropdown-item" href="/logout">
                        <settingSvgIcon
                            width="16px"
                            height="16px"
                            color="currentColor"
                        />
                        <span class="ms-2">Profile Setting</span>
                    </a> -->
                </div>
            </div>
        </ul>
        <a href="/demo" class="ms-2">
            <span class="badge bg-danger py-2 px-3">Demo</span>
        </a>
    </nav>
</template>

<style scoped>
.top-nav-dropdown {
    position: absolute;
    border-radius: 4px;
    overflow: hidden;
    box-shadow: 1px 1px 76px rgba(214, 208, 208, 0.479);
    top: 120%;
    right: 0px;
    z-index: 4;
    width: max-content;
}
.top-nav-dropdown-item {
    display: flex;
    align-items: center;
    padding: 6px 16px;
    background-color: white;
    color: #757779;
    width: 100%;
}
.top-nav-dropdown-item:hover {
    background-color: #2ba8f3;
    color: white;
}
</style>
