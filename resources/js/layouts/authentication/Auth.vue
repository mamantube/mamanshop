<script setup lang="ts">
import { RouterView } from "vue-router";


</script>

<template>
    <div class=" my-3">
        <div class="row">
            <div
                class="col d-flex justify-content-center align-items-center flex-column"
            >
                <img
                    width="100rem"
                    src="../../../../public/assets/img/mamanshop.png"
                    alt=""
                />
                <h2 class=" mb-3">MamanShop</h2>
            </div>
        </div>
        <RouterView class=" container" />
    </div>
</template>
