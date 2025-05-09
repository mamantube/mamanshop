<script setup>
import api from "../api";
import { ref, onMounted } from "vue";
import { formatIDR } from "../utils/formatIDR";
import { debounce } from "lodash";

let productList= ref([]);

const products = debounce (async () => {
  try {
    await api.get("products").then((response) => {
      productList.value = response.data.data;
      console.log(productList.value);
    });
  } catch (error) {
    console.log("error");
  }
}, 500);

onMounted(products);
</script>

<template>
  <div>
    <div class="row">
      <div class="col d-flex justify-content-center">
        <div class="w-75">
          <div
            id="carouselExampleAutoplaying"
            class="carousel slide"
            data-bs-ride="carousel"
          >
            <div class="carousel-inner">
              <div class="carousel-item active">
                <img
                  src="/public/assets/img/jumbo1.png"
                  class="d-block w-100"
                  alt="..."
                />
              </div>
              <div class="carousel-item">
                <img
                  src="/public/assets/img/jumbo2.png"
                  class="d-block w-100"
                  alt="..."
                />
              </div>
              <div class="carousel-item">
                <img
                  src="/public/assets/img/jumbo3.png"
                  class="d-block w-100"
                  alt="..."
                />
              </div>
            </div>
            <button
              class="carousel-control-prev"
              type="button"
              data-bs-target="#carouselExampleAutoplaying"
              data-bs-slide="prev"
            >
              <span
                class="carousel-control-prev-icon"
                aria-hidden="true"
              ></span>
              <span class="visually-hidden">Previous</span>
            </button>
            <button
              class="carousel-control-next"
              type="button"
              data-bs-target="#carouselExampleAutoplaying"
              data-bs-slide="next"
            >
              <span
                class="carousel-control-next-icon"
                aria-hidden="true"
              ></span>
              <span class="visually-hidden">Next</span>
            </button>
          </div>
        </div>
      </div>
    </div>
    <div class="row my-5">
      <div class="col text-center">
        <p class=" fs-2 fw-semibold">Kenapa belanja harus di MamanShop?</p>
      </div>
    </div>
    <div class="row">
      <div class="col py-3 px-3 text-center" sm="12" lg="4">
        <p class=" fw-medium">Fashion Trendy Harga Bersahabat</p>
        <p>Di toko kami, kamu bisa tampil stylish setiap hari dengan harga yang ramah di dompet. Cocok untuk kamu yang ingin tetap modis tanpa harus boros!</p>
      </div>
      <div class="col py-3 px-3 text-center" sm="12" lg="4">
        <p class=" fw-medium">Belanja Aman Style Maksimal</p>
        <p>produk yang kamu lihat adalah produk yang akan kamu terima — pengemasan rapi dan pengiriman cepat. Jadi kamu bisa tampil maksimal, tanpa drama!</p>
      </div>
      <div class="col py-3 px-3 text-center">
        <p class=" fw-medium">Tampil Stylish Setiap Hari</p>
        <p>Kami hadir dengan pilihan fashion lengkap mulai dari casual, semi-formal, hingga outfit kekinian yang cocok untuk semua suasana.</p>
      </div>
    </div>
    <div class="row">
      <div class="col my-5">
        <div class=" d-flex justify-content-center gap-3">
          <div
            v-for="product in productList"
            :key="product.id"
            class="card"
            style="width: 10rem"
          >
            <img
              :src="product.image"
              alt="..."
            />
            <div class="card-body text-center">
              <h5 class="card-title">{{ product.product_name }}</h5>
              <p class="card-text">
                {{ formatIDR(product.price) }}
              </p>
              <a href="/auth/login" class="btn btn-primary">Detail Produk</a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col text-center">
        <router-link to="/products">
          <button class=" btn btn-outline-primary">Lihat produk lainnya</button>
        </router-link>
      </div>
    </div>
  </div>
</template>