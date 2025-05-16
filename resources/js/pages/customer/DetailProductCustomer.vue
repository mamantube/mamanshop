<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import api from '../../api';
import { formatIDR } from '../../utils/formatIDR';
import Swal from 'sweetalert2';


const route = useRoute();
const router = useRouter();
const product = ref({});
const loading = ref(false);
const id = ref({});
const error = ref("");
const token = localStorage.getItem("token");
const quantity = ref(1)



const detailProduct = async () => {
    loading.value = true;

    try {
        await api.get(`products/${route.params.id}`)
        .then((response) => {
            product.value = response.data.data;
            id.value = response.data.data.id
        })
    } catch (error) {
        error.value = error.response?.data?.message || "Tidak bisa memuat detail produk";
        console.error(error);

    } finally {
        loading.value = false
    }
}

onMounted(detailProduct);

const addToCart = async () => {
    try {
        await api.post("cart", {product_id: product.value.id, quantity: quantity.value}, {
            headers: {
                "Authorization": `Bearer ${token}`,
            }
        })
        Swal.fire("Produk ditambahkan ke dalam keranjang", "", "success");
        router.push("/customer/cart-list")
    } catch (error) {
        console.error("Error menambahkan ke keranjang:", error.response);
        const errorMsg = error.response?.data?.message || "Produk gagal ditambahkan";
        Swal.fire("Error", errorMsg, "error");
    }    
}
</script>

<template>
    <div class="container mt-5">
        <div v-if="loading" class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Memuat product....</span>
            </div>
        </div>

        <div v-else-if="error" class="alert alert-danger text-center">
            {{ error }}
        </div>

        <div v-else>
            <div class="row vh-100">
                <div class="col">
                    <img
                        :src="product.image"
                        alt="Product Image"
                        style="width: 20rem; max-width: 762px;"
                    />
                </div>
                <div class="col">
                    <h6>
                        Nama Produk:
                    </h6>
                    <p>
                        {{ product.product_name }}
                    </p>
                    <h6>
                        Harga:
                    </h6>
                    <p>
                        {{ formatIDR(product.price) }}
                    </p>
                    <h6>
                        Stok:</h6>
                    <p>
                        {{ product.stock }} Pcs
                    </p>
                    <h6>
                        Deskripsi:
                    </h6>
                    <p>
                        {{ product.description }}
                    </p>

                    <div class=" row">
                        <div class="col col-md-4">
                            <input type="number" v-model="quantity" min="1" :max="product.stock" class="form-control w-100" />
                            <div class=" form-text">Masukkan jumlah pesanan</div>
                        </div>
                        <div class="col ">
                            <button @click="addToCart" class="btn px-5 py-2" style="background-color: #FF4433;/">
                                <i class="bi bi-cart-plus-fill" style="color: white;"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

