import { createRouter, createWebHistory } from "vue-router";

const router = createRouter({
    mode: "hash",
    history: createWebHistory(),

    routes: [
        {
            path: "/admin",
            name: "admin",
            component: () => import("../views/Admin.vue"),
            children: [
                // dashboard route
                {
                    name: "dashboard",
                    path: "",
                    component: () =>
                        import("../modules/dashboard/Dashboard.vue"),
                },

                // Currency route
                {
                    name: "currency",
                    path: "currency",
                    component: () =>
                        import("../modules/currency/Currencies.vue"),
                },

                // Unit Route
                {
                    name: "unit",
                    path: "unit",
                    component: () => import("../modules/unit/Units.vue"),
                },

                // Tax Route
                {
                    name: "tax",
                    path: "tax",
                    component: () => import("../modules/tax/Taxes.vue"),
                },

                // Warehouse Route
                {
                    name: "warehouse",
                    path: "warehouse",
                    component: () =>
                        import("../modules/warehouse/Warehouses.vue"),
                },

                // Brand Route
                {
                    name: "brand",
                    path: "brand",
                    component: () => import("../modules/brand/Brands.vue"),
                },

                // Product Category Route
                {
                    name: "product-category",
                    path: "product-category",
                    component: () => import("../modules/product-category/ProductCategories.vue"),
                },

                // Product Route
                {
                    name: "product",
                    path: "product",
                    component: () => import("../modules/product/Products.vue"),
                },

                // Customer Route
                {
                    name: "customer",
                    path: "customer",
                    component: () => import("../modules/customer/Customers.vue"),
                },

                // Supplier Route
                {
                    name: "supplier",
                    path: "supplier",
                    component: () => import("../modules/supplier/Suppliers.vue"),
                },

                // Account Route
                {
                    name: "account",
                    path: "account",
                    component: () => import("../modules/account/Accounts.vue"),
                },

                // Account Adjustment Route
                {
                    name: "account_adjustment",
                    path: "account-adjustment",
                    component: () => import("../modules/account-adjustment/Adjustments.vue"),
                },

                // Sale Route
                {
                    name: "sale",
                    path: "sale",
                    component: () => import("../modules/sale/Sales.vue"),
                },
                {
                    name: "new_sale",
                    path: "new-sale",
                    component: () => import("../modules/sale/AddSale.vue"),
                },

                // Purchase Route
                {
                    name: "purchase",
                    path: "purchase",
                    component: () => import("../modules/purchase/Purchases.vue"),
                },
                {
                    name: "new_purchase",
                    path: "new-purchase",
                    component: () => import("../modules/purchase/AddPurchase.vue"),
                },
            ],
        },
    ],
});

export default router;
