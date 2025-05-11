<script setup>
import { debounce } from 'lodash';
import api from '../../api';
import { ref, onMounted } from 'vue';

const id = localStorage.getItem('user_id');
const token = localStorage.getItem("token");
const isLoading = ref(false);

console.log(id)

const user = ref(null);
const cart = ref({
    items: [],
    total_price: 0,
})

const detailUser = debounce(async () => {
    isLoading.value = true;
    try {
        await api.get(`users/${id}`, {
            headers: {
                "Authorization": `Bearer ${token}`
            }
        }).then((response) => {
            user.value = response.data.data.user;
            cart.value = response.data.data.cart;
        })
        console.log(user);
        console.log(cart); 
    } catch (error) {
        console.error(error)        
    } finally {
        isLoading.value = false
    }
}, 200)

onMounted(detailUser);
</script>

<template>
    <div>
        <h1 class=" text-center">Keranjang Saya</h1>
        <div>
            <h3>Belum dibayar</h3>
        </div>
        <div v-if="isLoading" class=" swal2-loading">
            Memuat data.....
        </div>
        <div v-for="item in cart.items" :key="item.id">
            {{ item.product.product_name }} - {{ item.product.quantity }} X {{ item.product.price }} 
        </div>
    </div>
</template>