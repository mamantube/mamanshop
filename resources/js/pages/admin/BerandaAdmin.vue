<script setup>
import { ref, onMounted, computed } from "vue";
import { debounce } from "lodash";
import api from "../../api";
import { formatIDR } from "../../utils/formatIDR";

const isLoading = ref(false);
const token = localStorage.getItem("token");
const cart = ref([]);

const allCart = debounce(async () => {
    isLoading.value = true;
    try {
        const response = await api.get("cart", {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        });

        cart.value = response.data.data;

        console.log(cart);
    } catch (error) {
        console.error(error);
    } finally {
        isLoading.value = false;
    }
}, 200);

const pendingPayment = computed(() => {
    return cart.value.filter((item) => item.payment === "pending");
});

const successPayment = computed(() => {
    return cart.value.filter((item) => item.payment === "success");
});

onMounted(allCart);
</script>

<template>
    <div class="container vh-100">
        <h1 class="text-center my-5">Data Transaksi</h1>
        <div class="row">
            <div class="col">
                <h6>Belum melakukan pembayaran</h6>
            </div>
        </div>
        <div v-if="isLoading" class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading....</span>
            </div>
            <p class="mt-2">Memuat data....</p>
        </div>
        <div
            v-else-if="pendingPayment.length === 0 && !isLoading"
            class="text-center my-5"
        >
            <span class="alert alert-danger w-25"> Tidak ada produk </span>
        </div>
        <div v-else class=" container">
            <div v-for="item in pendingPayment" :key="item.id">
                <div class="row">
                    <div class="col">
                        <div class="card mt-4 cart">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4 col-lg-2 align-self-center">
                                        <img
                                            :src="item.product.image"
                                            alt=""
                                            width="75rem"
                                        />
                                    </div>
                                    <div class="col-4 col-lg-3 align-self-center">
                                        <span class="fw-semibold">
                                            {{ item.product.product_name }}
                                        </span>
                                        <br />
                                        <span class="fw-semibold">
                                            {{ item.quantity }} Pcs
                                        </span>
                                    </div>
                                    <div class="col-4 col-lg-3 align-self-center">
                                        Total harga: <br />
                                        <span class="fw-semibold">
                                            {{ formatIDR(item.total_price) }}
                                        </span>
                                    </div>
                                    <div class="col-6 col-md-6 col-lg-2 mt-0 mt-3 mt-lg-0 align-self-center">
                                        {{ item.user.user_name }} <br />
                                        {{ item.user.email }} <br>
                                        {{ item.user.phone }} <br>
                                    </div>
                                    <div class="col-6 col-lg-2 col-md-6 align-self-center text-center">
                                        <span class="badge bg-danger">
                                            Belum bayar
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col">
                <h6>Sudah melakukan pembayaran</h6>
            </div>
        </div>
        <div v-if="isLoading">
             <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading....</span>
            </div>
            <p class="mt-2">Memuat data....</p>
        </div>
        <div v-else-if="successPayment.length === 0 && !isLoading" class=" text-center my-5">
            <span class="alert alert-danger w-25">
                Belum ada produk yang sudah dibayar
            </span>
        </div>
        <div v-else class=" container">
            <div v-for="item in successPayment" :key="item.id">
                <div class="row">
                    <div class="col">
                        <div class="card mt-4 cart">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4 col-lg-2 align-self-center">
                                        <img
                                            :src="item.product.image"
                                            alt=""
                                            width="75rem"
                                        />
                                    </div>
                                    <div class="col-4 col-lg-3 align-self-center">
                                        <span class="fw-semibold">
                                            {{ item.product.product_name }}
                                        </span>
                                        <br />
                                        <span class="fw-semibold">
                                            {{ item.quantity }} Pcs
                                        </span>
                                    </div>
                                    <div class="col-4 col-lg-3 align-self-center">
                                        Total harga: <br />
                                        <span class="fw-semibold">
                                            {{ formatIDR(item.total_price) }}
                                        </span>
                                    </div>
                                    <div class="col-6 col-md-6 col-lg-2 mt-0 mt-3 mt-lg-0 align-self-center">
                                        {{ item.user.user_name }} <br>
                                        {{ item.user.email }} <br>
                                        {{ item.user.phone }}
                                    </div>
                                    <div class="col-6 col-lg-2 col-md-6 align-self-center text-center">
                                        <span class="badge bg-success">
                                            Sudah bayar
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
