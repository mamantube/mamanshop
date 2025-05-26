<script setup lang="ts">
import Navbar from "../../components/Navbar.vue";
import Footer from "../../components/Footer.vue";
</script>

<template>
    <div>
        <Navbar />
        <router-view class=" container"></router-view>
        <Footer />
    </div>
</template>
