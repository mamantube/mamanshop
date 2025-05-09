<script setup lang="ts">
import { RouterView } from "vue-router";


</script>

<template>
    <div class="container-fluid mt-4">
        <div class="row">
            <div
                class="col d-flex justify-content-center align-items-center flex-column"
            >
                <img
                    width="100rem"
                    src="../../../../public/assets/img/mamanshop.png"
                    alt=""
                />
                <h2>MamanShop</h2>
            </div>
        </div>
        <RouterView />
    </div>
</template>
