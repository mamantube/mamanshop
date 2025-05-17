<script setup>
import { ref, onMounted, computed, watch } from "vue";
import api from "../../api";
import { debounce } from "lodash";
import { formatIDR } from "../../utils/formatIDR";

const productList = ref([]);
const isLoading = ref(false);
const searchQuery = ref("");
const meta = ref({
    current_page: 1,
    last_page: 1,
    per_page: 8,
    total: 0,
});

const products = debounce(async () => {
    isLoading.value = true;
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
        isLoading.value = false;
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
    <div class=" container-fluid">
        <div class="row mt-5">
            <div class="col d-flex justify-content-center">
                <div class="input-group w-50">
                    <input
                        v-model="searchQuery"
                        type="text"
                        class="form-control"
                        placeholder="Cari produk...."
                        aria-label="Search Products"
                    />
                </div>
            </div>
        </div>
        <div class="row mt-5 ms-5">
            <div class="col">
                <router-link to="/admin/add-product">
                    <button type="button" class="btn btn-primary">
                        Tambah Produk
                    </button>
                </router-link>
            </div>
        </div>
        <div class="row d-flex justify-content-center">
            <div v-if="isLoading" class="text-center my-5">
                <div class="col vh-100">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading....</span>
                    </div>
                    <p class="mt-2">Memuat data....</p>
                </div>
            </div>
            <div v-else-if="productList.length === 0" class="text-center my-5">
                <div class="col vh-100">
                    <span class="alert alert-danger w-25">
                        Produk tidak ditemukan
                    </span>
                </div>
            </div>
            <div
                v-else
                class="d-flex flex-wrap justify-content-center gap-4 my-5"
            >
                <div v-for="product in productList" :key="product.id">
                    <div class="col my-5 col-md-2 col-sm-4 product">
                        <div class="card" style="width: 13rem">
                            <img :src="product.image" alt="" />
                            <div class="card-body text-center">
                                <h5 class="card-title">
                                    {{ product.product_name }}
                                </h5>
                                <p class="card-text">
                                    {{ formatIDR(product.price) }}
                                </p>
                                <router-link
                                    :to="`/admin/products/${product.id}`"
                                >
                                    <button class="btn btn-outline-primary">
                                        Detail Produk
                                    </button>
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
            <div
                v-if="meta.total > 0"
                class="d-flex justify-content-center mt-4"
            >
                <nav>
                    <ul class="pagination">
                        <li
                            class="page-item"
                            :class="{ disabled: meta.current_page === 1 }"
                        >
                            <a
                                class="page-link"
                                href="#"
                                @click.pervent="goToPage(meta.current_page - 1)"
                            >
                                <
                            </a>
                        </li>
                        <li
                            v-for="page in pageNumber"
                            :key="page"
                            class="page-item"
                            :class="{ active: page === meta.current_page }"
                        >
                            <a
                                class="page-link"
                                href="#"
                                @click.prevent="goToPage(page)"
                            >
                                {{ page }}
                            </a>
                        </li>
                        <li
                            class="page-item"
                            :class="{
                                disabled: meta.current_page === meta.last_page,
                            }"
                        >
                            <a
                                class="page-link"
                                href="#"
                                @click.prevent="goToPage(meta.current_page + 1)"
                            >
                                >
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</template>
