<script setup lang="ts">
import { RouterLink } from 'vue-router';

</script>

<template>
    <nav class="navbar navbar-expand-lg bg-body-tertiary mb-4">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img
                    width="60rem"
                    src="../../../public/assets/img/mamanshop.png"
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
                        <router-link class="nav-link fw-semibold" to="/">Beranda</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link class="nav-link fw-semibold" to="/products"
                        >Produk</router-link
                        >
                    </li>
                </ul>
                <div class="d-flex">
                    <RouterLink to="/auth/regist">
                        <button type="button" class="btn btn-primary me-2">
                            Daftar
                        </button>
                    </RouterLink>
                    <router-link to="/auth/login">
                        <button type="button" class="btn btn-primary">
                            Masuk
                        </button>
                    </router-link>
                </div>
            </div>
        </div>
    </nav>
</template>
