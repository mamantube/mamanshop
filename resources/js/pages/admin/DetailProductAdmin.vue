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
    <div class="container my-5 vh-100">
        <div v-if="loading" class="text-center ">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Memuat product....</span>
            </div>
        </div>

        <div v-else-if="error" class="alert alert-danger text-center">
            {{ error }}
        </div>

        <div v-else>
            <div class="row detail-product">
                <div class="col-12 col-lg-6 bg">
                    <img
                    :src="product.image"
                    alt="Product Image"
                    style="width: 20rem; max-width: 762px;"
                />
                </div>
                <div class="col-12 col-lg-6 mt-3 mt-md-3 mt-xl-0 ps-lg-5">
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
                    <div class=" d-flex gap-2 mt-5">
                        <router-link :to="`/admin/update-product/${id}`">
                            <button class="btn btn-secondary">
                                Update produk
                            </button>
                        </router-link>
                        <button @click="deleteProduct" class="btn btn-danger">
                            Hapus produk
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
