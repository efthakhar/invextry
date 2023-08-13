<script setup>
import { onMounted, ref } from "vue";
import { defineAsyncComponent } from "vue";
import { useAuthStore } from "../stores/authStore";
import { useSidebar } from "../stores/sidebar";

const sidebarStore = useSidebar();

const authStore = useAuthStore();
let permissions = ref(authStore.permissions);

const props = defineProps({ link_details: Object });

const show_sublinks = ref(false);
const sublinks = ref([]);
const hidden = ref(true);

// watch(() => route.fullPath, () => {

// });

function handleNavlinkClick() {
    if (window.innerWidth <= 767) {
        useSidebar().toggle();
    }
}

function isHidden() {
    // for only links
    if (
        props.link_details.permission &&
        permissions.value.includes(props.link_details.permission)
    ) {
        return (hidden.value = false);
    }

    // for links with sublinks
    if (props.link_details.sub_links) {
        for (
            let index = 0;
            index < props.link_details.sub_links.length;
            index++
        ) {
            if (
                permissions.value.includes(
                    props.link_details.sub_links[index].permission
                )
            ) {
                hidden.value = false;
                break;
            }
        }
    }
}

const Icon = defineAsyncComponent(() =>
    import(`../assets/icons/${props.link_details.icon_name}.vue`)
);

onMounted(() => {
    //   userStore.getUser().then((res) => {
    // (permissions.value = userStore.permissions),
    isHidden();
    //   });

    sublinks.value = props.link_details.sub_links;
    if (sublinks.value) {
        sublinks.value = sublinks.value.map((obj) => obj.link);
    }
});
</script>

<template>
    <div>
        <!-- For Links Only -->
        <div v-if="hidden == false && !link_details.sub_links">
            <li
                class="sidebar-item"
                :class="{ active: $route.fullPath == link_details.link }"
            >
                <RouterLink
                    @click="handleNavlinkClick"
                    class="sidebar-link"
                    :to="link_details.link"
                    v-if="!link_details.sub_links"
                >
                    <Icon />
                    <span>{{ link_details.label }}</span>
                </RouterLink>
            </li>
        </div>

        <!-- For Links with SubLinks -->
        <div v-if="hidden == false && link_details.sub_links">
            <li
                class="sidebar-item has-sub"
                :class="{ active: sublinks.includes($route.fullPath) }"
            >
                <span
                    href="#"
                    class="sidebar-link"
                    @click="show_sublinks = !show_sublinks"
                >
                    <Icon />
                    <span>{{ link_details.label }}</span>
                </span>
                {{}}
                <ul class="submenu" :class="{ active: show_sublinks }">
                    <div v-for="slink in link_details.sub_links">
                        <RouterLink
                            v-if="permissions.includes(slink.permission)"
                            @click="handleNavlinkClick"
                            class="sidebar-sublink"
                            :class="{ active: $route.fullPath == slink.link }"
                            :to="slink.link"
                        >
                            <span>{{ slink.label }}</span>
                        </RouterLink>
                    </div>
                </ul>
            </li>
        </div>
    </div>
</template>
