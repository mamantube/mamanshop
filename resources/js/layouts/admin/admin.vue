<script setup lang="ts">
import { RouterLink, RouterView } from "vue-router";
import { useRouter } from "vue-router";
import { onBeforeMount } from "vue";
import Footer from "../../components/Footer.vue";

const router = useRouter();

const token = localStorage.getItem("token");
const role = localStorage.getItem("role");

if (!token && role !== "admin") {
    router.push("/forbidden");
}

onBeforeMount(() => {
    if (!token) return router.push("/");
});

const onLogout = () => {
    localStorage.clear();
    router.push("/");
};
</script>

<template>
    <nav class="navbar navbar-expand-lg bg-body-tertiary mb-4">
        <div class=" container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img
                    width="60rem"
                    src="../../../../public/assets/img/mamanshop.png"
                    alt=""
                />
                <h2>MamanShop</h2>
            </a>
            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent"
                aria-expanded="false"
                aria-label="Toggle navigation"
            >
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <router-link
                            class="nav-link fw-semibold"
                            to="/admin/beranda"
                            >Data Penjualan</router-link
                        >
                    </li>
                    <li class="nav-item">
                        <router-link class="nav-link fw-semibold" to="/admin/products">
                            Produk
                        </router-link
                        >
                    </li>
                    <li class="nav-item">
                        <router-link class="nav-link fw-semibold" to="/admin/customer-list">
                            Data pengguna
                        </router-link>
                    </li>
                </ul>
                <div class="d-flex">
                    <button
                        @click="onLogout"
                        type="button"
                        class="btn btn-primary"
                    >
                        Logout
                    </button>
                </div>
            </div>
        </div>
    </nav>
    <router-view class=" container"></router-view>
    <Footer />
</template>
