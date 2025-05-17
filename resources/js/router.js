import { createRouter, createWebHistory } from "vue-router";
import Auth from "./layouts/authentication/Auth.vue";
import Landing from "./layouts/landing/Landing.vue";
import Product from "./pages/Product.vue";
import LoginPage from "./pages/LoginPage.vue";
import Regist from "./pages/Regist.vue";
import Beranda from "./pages/Beranda.vue";
import BerandaAdmin from "./pages/admin/BerandaAdmin.vue";
import Admin from "./layouts/admin/admin.vue";
import ProductsAdmin from "./pages/admin/ProductsAdmin.vue";
import TransaksiAdmin from "./pages/admin/TransaksiAdmin.vue";
import AddProduct from "./pages/admin/AddProduct.vue";
import DetailProductAdmin from "./pages/admin/DetailProductAdmin.vue";
import UpdateProductAdmin from "./pages/admin/UpdateProductAdmin.vue";
import BerandaCostumer from "./pages/customer/BerandaCostumer.vue";
import DashboardCustomer from "./layouts/customer/DashboardCustomer.vue";
import ProductsCustomer from "./pages/customer/ProductsCustomer.vue";
import DetailProductCustomer from "./pages/customer/DetailProductCustomer.vue";
import CustomerList from "./pages/admin/CustomerList.vue";
import ForbiddenAccess from "./pages/ForbiddenAccess.vue";
import CartList from "./pages/customer/CartList.vue";
import ContactUs from "./pages/customer/ContactUs.vue";


const routes = [
    { path: "/", component: Landing,
        children: [
            { path: "", component: Beranda},
            { path: "/products", component: Product},
        ],
    },
    { path: "/auth", component: Auth,
        children: [
            { path: "login", component: LoginPage},
            { path: "regist", component: Regist},
        ],
    },
    {
        path: "/admin", component: Admin,
            children: [
                { path: "beranda", component: BerandaAdmin},
                { path: "products", component: ProductsAdmin},
                { path: "transaksi", component: TransaksiAdmin},
                { path: "add-product", component: AddProduct},
                { path: "products/:id", component: DetailProductAdmin},
                {path: "update-product/:id", component: UpdateProductAdmin},
                { path: "customer-list", component: CustomerList},

            ]
    },
    {
        path: "/customer", component: DashboardCustomer,
            children: [
                { path: "beranda", component: BerandaCostumer},
                { path: "products", component: ProductsCustomer},
                { path: "products/:id", component: DetailProductCustomer},
                { path: "cart-list", component: CartList},
                { path: "contact-us", component: ContactUs}
            ]
    },
    {
        path: "/forbidden", component: ForbiddenAccess
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router