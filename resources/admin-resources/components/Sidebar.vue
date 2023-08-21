<script setup>
import { useRoute } from "vue-router";
import SideNavLink from "../components/SideNavLink.vue";
import crossSvgIcon from "../assets/icons/cross-svg-icon.vue";
import { ref, watch } from "vue";
import { useSidebar } from "../stores/sidebar";
const sidebarStore = useSidebar();
const route = useRoute();

const navlinks = [
    {
        label: "dashboard",
        link: "/admin",
        icon_name: "dashboard-svg-icon",
        permission: "view_user",
    },

    {
        label: "products",
        link: " ",
        icon_name: "square-svg-icon",
        sub_links: [
            {
                label: "product list",
                link: "/admin/product",
                permission: "view_product",
            },
            {
                label: "category",
                link: "/admin/product-category",
                permission: "view_product_category",
            },
            {
                label: "brand",
                link: "/admin/brand",
                permission: "view_brand",
            },
            {
                label: "unit",
                link: "/admin/unit",
                permission: "view_unit",
            },
        ],
    },

    {
        label: "Sales",
        link: " ",
        icon_name: "cart-svg-icon",
        sub_links: [
            {
                label: "Sale List",
                link: "/admin/sale",
                permission: "view_sale",
            },
            {
                label: "New Sale",
                link: "/admin/new-sale",
                permission: "create_sale",
            },
        ],
    },

    {
        label: "Purchases",
        link: "/admin/purchase",
        icon_name: "beg-svg-icon",
        sub_links: [
            {
                label: "Purchase List",
                link: "/admin/purchase",
                permission: "view_purchase",
            },
            {
                label: "New Purchase",
                link: "/admin/new-purchase",
                permission: "create_purchase",
            },
        ],
    },

    {
        label: "Customers",
        link: "/admin/customer",
        icon_name: "customer-svg-icon",
        permission: "view_customer",
    },

    {
        label: "Suppliers",
        link: "/admin/supplier",
        icon_name: "supplier-svg-icon",
        permission: "view_supplier",
    },

    {
        label: "accounting",
        link: " ",
        icon_name: "booklet-svg-icon",
        sub_links: [
            {
                label: "account",
                link: "/admin/account",
                permission: "view_account",
            },
            {
                label: "balance adjustment",
                link: "/admin/account-adjustment",
                permission: "view_account_adjustment",
            },
        ],
    },

    {
        label: "settings",
        link: " ",
        icon_name: "setting-svg-icon",
        sub_links: [
            {
                label: "warehouse",
                link: "/admin/warehouse",
                permission: "view_warehouse",
            },
            {
                label: "currency",
                link: "/admin/currency",
                permission: "view_currency",
            },
            {
                label: "tax",
                link: "/admin/tax",
                permission: "view_tax",
            },
        ],
    },
];
</script>

<template>
    <div id="sidebar" :class="{ active: sidebarStore.open }">
        <div class="sidebar-wrapper active">
            <div class="sidebar-header d-flex">
                <div>
                    <img
                        src="../assets/img/invextry-logo.png"
                        class="cursor-pointer img-fluid"
                        @click="$router.push({ name: 'dashboard' })"
                    />
                </div>
                <div class="small-screen-menu-icon ms-3">
                    <crossSvgIcon
                        width="25px"
                        height="25px"
                        @click="sidebarStore.toggle()"
                    />
                </div>
            </div>
            <div class="sidebar-menu">
                <ul class="menu">
                    <SideNavLink
                        v-for="link in navlinks"
                        :key="link.link"
                        :link_details="link"
                    />
                </ul>
            </div>
        </div>
    </div>
</template>
