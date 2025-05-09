<script setup>
import api from '../api.js';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useForm, useField, ErrorMessage } from 'vee-validate';
import * as yup from "yup";
import Swal from 'sweetalert2';

const router = useRouter();
const isLoading = ref(false)

const schema = yup.object({
    email: yup.string().email("Format email salah").required("Email wajib diidi"),
    password: yup.string().required("Password wajib diisi")
})

const { handleSubmit, errors} = useForm({
    validationSchema: schema,
});

const { value: email, meta: emailMeta, errorMessage: emailError } = useField("email");
const { value: password, meta: passwordMeta, errorMessage: passwordError } = useField("password")

const handleLogin = handleSubmit ( async (values) => {
    isLoading.value = true;

    try {
        await api.post("login", values)
        .then((response) => {
            let {token} = response.data;
            let {role, user_name} = response.data.user;
            localStorage.setItem("role", role);
            localStorage.setItem("user_name", user_name)
            localStorage.setItem("token", token);
    
            router.push(role === "admin" ? "/admin/beranda" : "/customer/beranda")
        })

    } catch (error) {
        let { message } = error.response.data;
        Swal.fire({
            title: "Login gagal",
            text: `${message}`
        })
    } finally {
        isLoading.value = false
    }
});


</script>

<template>
    <div class=" container-fluid">
        <div class="row">
            <div class="col d-flex justify-content-center">
                <div class=" border border-1 border-black w-50">
                    <form @submit.prevent="handleLogin" class=" p-4">
                        <div class="mb-3">
                            <label for="email" class="form-label"
                                >Email address</label
                            >
                            <input
                                v-model="email"
                                type="email"
                                class="form-control"
                                id="email"
                                aria-describedby="emailHelp"
                                name="email"
                                :class="{
                                    'is-invalid' : emailMeta.touched && emailError
                                }"
                            />
                            <div v-if="errors.email" class=" invalid-feedback">
                                <ErrorMessage name="email" />
                            </div>
                            <div id="emailHelp" class="form-text">
                                We'll never share your email with anyone else.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label"
                                >Password</label
                            >
                            <input
                                v-model="password"
                                type="password"
                                class="form-control"
                                id="password"
                                name="password"
                                :class="{
                                    'is-invalid' : passwordMeta.touched && passwordError
                                }"
                            />
                        </div>
                        <div v-if="errors.password" class=" invalid-feedback">
                            <ErrorMessage name="password" />
                        </div>                       
                        <button type="submit" class="btn btn-primary w-100" :disabled="isLoading">
                            <span v-if="isLoading" class=" spinner-border spinner-border-sm"></span>
                            <span v-if="!isLoading">Login</span>
                        </button>
                        <router-link to="/" class="btn btn-primary mt-4 w-100">
                            Kembali ke beranda
                        </router-link>
                        <div class="d-flex justify-content-center">
                            <p>Belum mempunyai akun? Silahkan daftar 
                                <router-link to="/auth/regist">Di sini</router-link>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
