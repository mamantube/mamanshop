<script setup lang="ts">
import { RouterLink } from 'vue-router';
import { useRouter } from 'vue-router';
import Footer from '../../components/Footer.vue';
import Swal from 'sweetalert2';

const router = useRouter()

const handleLogout = () => {
    localStorage.clear()

    router.push("/")
}

let userName = localStorage.getItem("user_name")

const profile = () => {
    Swal.fire({
        title: "Fitur ini belum tersedia",
        icon: "info"
    })
}


</script>

<template>
    <nav class="navbar navbar-expand-lg bg-body-tertiary mb-4">
        <div class="container-fluid">
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
                        <router-link class="nav-link fw-semibold" to="/">Beranda</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link class="nav-link fw-semibold" to="/customer/products"
                            >Produk</router-link
                        >
                    </li>
                    <li class="nav-item">
                        <router-link class="nav-link fw-semibold" to="/customer/contact-us"
                            >Hubungi Kami</router-link
                        >
                    </li>
                    
                </ul>

                <div class="d-flex">
                    <ul class=" navbar-nav">
                        <li class="nav-item dropdown">
                            <a
                                class="nav-link dropdown-toggle text-base"
                                href="#"
                                role="button"
                                data-bs-toggle="dropdown"
                                aria-expanded="false"
                            >
                            <i class="bi bi-person-circle"></i> {{ userName }} 
                                
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a @click="profile" class="dropdown-item" href="#">Profil</a>
                                </li>
                                <li>
                                    <router-link to="/customer/cart-list" class=" dropdown-item">
                                        Keranjang Saya
                                    </router-link>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#" @click="handleLogout
                                    ">Logout</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <router-view></router-view>
    <Footer></Footer>
</template>
