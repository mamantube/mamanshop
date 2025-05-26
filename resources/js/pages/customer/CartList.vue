<script setup>
import { debounce } from "lodash";
import api from "../../api";
import { ref, onMounted, computed } from "vue";
import Swal from "sweetalert2";
import { formatIDR } from "../../utils/formatIDR";

const id = localStorage.getItem("user_id");
const token = localStorage.getItem("token");
const isLoading = ref(false);
const cart_id = ref();

const user = ref(null);
const cart = ref([]);

const getCart = debounce(async () => {
    isLoading.value = true;
    try {
        await api
            .get(`users/${id}`, {
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            })
            .then((response) => {
                user.value = response.data.data;
                cart.value = response.data.data.cart_items;
            });
        console.log(user);
        console.log(cart);
        console.log(cart_id);
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

onMounted(getCart);

const payment = async (cartItemId) => {
    try {
        const response = await api.post(`cart/payment/${cartItemId}`, null, {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        });

        await getCart();
        Swal.fire("", response.data.message, "success");
    } catch (error) {
        Swal.fire("Pembayaran gagal", "", "error");
        console.error(error);
    }
};

const removeCart = async (cartItemId) => {
    try {
        const response = await api.delete(`cart/${cartItemId}`, {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        });

        await getCart();

        Swal.fire("", response.data.message, "success");
    } catch (error) {
        Swal.fire("Gagal hapus produk dari keranjang", "", "error");
        console.error(error);
    }
};
</script>

<template>
    <div class=" container vh-100">
        <h1 class="text-center">Keranjang Saya</h1>
        <div class="mt-5">
            <h4>Belum dibayar</h4>
        </div>
        <div v-if="isLoading" class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading....</span>
            </div>
            <p class="mt-2">Memuat data....</p>
        </div>
        <div v-else-if="pendingPayment.length === 0 && !isLoading" class=" text-center my-5">
            <span class="alert alert-danger w-25">
                Tidak ada produk
            </span>
        </div>
        <div v-else class=" container">
            <div v-for="item in pendingPayment" :key="item.id">
                <div class="row">
                    <div class="col">
                        <div class="card mt-4 cart">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4 col-lg-3 align-self-center">
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
                                    <div class="col-12 col-lg-3 mt-3 mt-lg-0 align-self-center">
                                        <button
                                            @click="payment(item.id)"
                                            class="btn btn-outline-secondary"
                                        >
                                            Bayar
                                        </button>
                                        <button
                                            @click="removeCart(item.id)"
                                            class="btn btn-outline-danger ms-2"
                                        >
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <h4 class="mt-4">Sudah bayar</h4>
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
                <div class="card mt-4 cart">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4 col-lg-3 align-self-center">
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
                            <div class="col-12 col-lg-3 mt-3 mt-lg-0 align-self-center">
                                <span class="badge bg-success"
                                    >Pembayaran berhasil</span
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
