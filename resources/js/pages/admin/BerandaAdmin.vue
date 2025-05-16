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
    <h1 class="text-center my-5">Data Transaksi</h1>
    <div class="row">
        <div class="col ms-3">
            <h6>Belum melakukan pembayaran</h6>
        </div>
        <div class="row">
            <div class="col">
                <div v-if="isLoading">Memuat data.....</div>
                <div v-if="pendingPayment.length === 0 && !isLoading">
                    Tidak ada transaksi yang belum dibayar
                </div>
                <div v-for="item in pendingPayment" :key="item.id">
                    <div class="card mt-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col col-md-2">
                                    <img
                                        :src="item.product.image"
                                        alt=""
                                        width="75rem"
                                    />
                                </div>
                                <div class="col align-self-center">
                                    <span class="fw-semibold">
                                        {{ item.product.product_name }}
                                    </span>
                                    <br />
                                    <span class="fw-semibold">
                                        {{ item.quantity }} Pcs
                                    </span>
                                </div>
                                <div class="col align-self-center">
                                    Total harga: <br />
                                    <span class="fw-semibold">
                                        {{ formatIDR(item.total_price) }}
                                    </span>
                                </div>
                                <div class="col align-self-center">
                                    <span class="badge bg-danger">
                                        Belum bayar
                                    </span>
                                </div>
                                <div class="col align-self-center">
                                    {{ item.user.user_name }} <br>
                                    {{ item.user.email }}
                                    {{ item.user.phone }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col ms-3">
                <h6>Sudah melakukan pembayaran</h6>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div v-if="isLoading">Memuat data</div>
                <div v-if="successPayment.length === 0 && !isLoading">
                    Belum ada transaksi yang sudah dibayar
                </div>
                <div v-for="item in successPayment" :key="item.id">
                    <div class="card mt-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <img
                                        :src="item.product.image"
                                        alt=""
                                        width="75rem"
                                    />
                                </div>
                                <div class="col align-self-center">
                                    <span class="fw-semibold">
                                        {{ item.product.product_name }}
                                    </span>
                                    <br />
                                    <span class="fw-semibold">
                                        {{ item.quantity }} Pcs
                                    </span>
                                </div>
                                <div class="col align-self-center">
                                    Total harga: <br />
                                    <span class="fw-semibold">
                                        {{ formatIDR(item.total_price) }}
                                    </span>
                                </div>
                                <div class="col align-self-center">
                                    <span class=" badge bg-success">
                                       Sudah bayar
                                    </span>
                                </div>
                                <div class="col align-self-center">
                                    {{ item.user.user_name }}
                                    {{ item.user.email }}
                                    {{ item.user.phone }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
