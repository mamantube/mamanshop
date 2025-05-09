<script setup>
import { ref, onMounted, computed, watch } from "vue";
import api from "../../api";
import { debounce } from "lodash";
import { formatIDR } from "../../utils/formatIDR";

const productList = ref([]);
const loading = ref(false);
const searchQuery = ref("");
const meta = ref({
    current_page: 1,
    last_page: 1,
    per_page: 8,
    total: 0,
});

const products = debounce(async () => {
    loading.value = true;
    try {
        await api
            .get("products", {
                params: {
                    page: meta.value.current_page,
                    per_page: meta.value.per_page,
                    search: searchQuery.value,
                },
            })
            .then((response) => {
                productList.value = response.data.data;
                meta.value = response.data.meta;
            });
    } catch (error) {
        console.error("error", error);
    } finally {
        loading.value = false;
    }
}, 500);

const goToPage = (page) => {
    if (page >= 1 && page <= meta.value.last_page) {
        meta.value.current_page = page;
        products();
        window.scrollTo({ top: 0, behavior: "smooth" });
    }
};

watch(searchQuery, () => {
    meta.value.current_page = 1;
    products();
});

const pageNumber = computed(() => {
    const pages = [];
    const maxVisible = 5;

    let startPage = Math.max(
        1,
        meta.value.current_page - Math.floor(maxVisible / 2)
    );
    let endPage = Math.min(meta.value.last_page, startPage + maxVisible - 1);

    if (endPage - startPage + 1 < maxVisible) {
        startPage = Math.max(1, endPage - maxVisible + 1);
    }

    for (let i = startPage; i <= endPage; i++) {
        pages.push(i);
    }

    return pages;
});

onMounted(products);
</script>

<template>
    <div class="container vh-100">
        <div class="row">
            <div class="col">
                <div class="input-group">
                    <input
                        v-model="searchQuery"
                        type="text"
                        class="form-control"
                        placeholder="Cari produk...."
                        aria-label="Search Products"
                    />
                    <button
                        class="btn btn-outline-primary"
                        type="button"
                        @click="products"
                    >
                        <i class="bi bi-search">Cari</i>
                    </button>
                    <button v-if="searchQuery"
                        class="btn btn-outline-primary"
                        type="button"
                        @click="() => {searchQuery = ''; products();}"
                    >
                        <i class="bi bi-search">Cari</i>
                    </button>
                </div>
            </div>
        </div>
        <div class=" row mt-5">
            <div class=" col">
                <router-link to="/admin/add-product">
                    <button type="button" class="btn btn-primary">Tambah Produk</button>
                </router-link>
            </div>
        </div>
        <div class="row mt-8">
            <div class=" col">
                <div class="">
                    <div v-if="loading" class=" text-center py-5">
                        <div class=" spinner-border text-primary" role="status">
                            <span class=" visually-hidden">Loading....</span>
                        </div>
                        <p class=" mt-2">Memuat data....</p>
                    </div>
    
                    <div v-else class=" d-flex justify-content-center align-items-center mt-5">
                        <div v-if="productList.length === 0" class=" alert alert-warning text-center">
                            Produk tidak ditemukan
                        </div>
    
                        <div v-else class=" d-flex justify-content-center align-items-center my-4 gap-3">
                            <div v-for="product in productList" :key="product.id" class=" card shadow-sm" style="width: 10rem">
                                <img :src="product.image" alt="">
                                <div class=" card-body text-center">
                                    <h5 class=" card-title">
                                        {{ product.product_name }}
                                    </h5>
                                    <p class=" card-text">
                                        {{ formatIDR(product.price) }}
                                    </p>
                                    <router-link :to="`/admin/products/${product.id}`">
                                        <button class=" btn btn-outline-primary">Detail Produk</button>
                                    </router-link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div v-if="meta.total > 0" class=" d-flex justify-content-center mt-4">
                    <nav>
                        <ul class=" pagination">
                            <li class=" page-item" :class="{ disabled: meta.current_page === 1}">
                                <a class=" page-link" href="#" @click.pervent="goToPage(meta.current_page - 1)">
                                    <
                                </a>
                            </li>
                            <li v-for="page in pageNumber" :key="page" class=" page-item" :class="{ active: page === meta.current_page}">
                                <a class="page-link" href="#" @click.prevent="goToPage(page)">
                                    {{ page }}
                                </a>
                            </li>
                            <li class=" page-item" :class="{ disabled: meta.current_page === meta.last_page}">
                                <a class=" page-link" href="#" @click.prevent="goToPage(meta.current_page + 1)">
                                    >
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</template>
