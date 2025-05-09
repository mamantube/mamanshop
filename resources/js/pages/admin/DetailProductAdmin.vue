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
let token = localStorage.getItem("token")


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

const deleteProduct = async() => {
    const id = route.params.id
    const result = await Swal.fire({
        title: "Apakah anda yakin ingin menghapus produk ini?",
        confirmButtonText: "Ya",
        showCancelButton: "true",
        icon: "question",
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6"
    })
        if (result.isConfirmed) {
            await api.delete(`products/${id}`, {
                headers: {
                    Authorization : `Bearer ${token}`
                }
            })
            await Swal.fire("Produk berhasil dihapus", "", "success")
            router.push("/admin/products")

        } else if (result.isDismissed) {
            Swal.fire("Produk batal dihapus", "" , "info")
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
            <h1 class="mb-4">{{ product.product_name }}</h1>
            <div class="card">
                <img
                    :src="product.image"
                    class="card-img-top"
                    alt="Product Image"
                />
                <div class="card-body">
                    <h5 class="card-title">{{ product.product_name }}</h5>
                    <p class="card-text">
                        Harga: {{ formatIDR(product.price) }}
                    </p>
                    <p class="card-text">Stok: {{ product.stock }}</p>
                    <p class="card-text">
                        Deskripsi: {{ product.description }}
                    </p>
                    <div class=" d-flex gap-2">
                        <router-link :to="`/admin/update-product/${id}`">
                            <button class="btn btn-secondary">
                                Update produk
                            </button>
                        </router-link>
                        <button @click="deleteProduct" class="btn btn-danger">
                            Hapus produck
                        </button>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
