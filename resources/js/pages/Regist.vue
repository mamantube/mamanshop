<script setup>
import { ref } from "vue";
import api from "../api.js";
import { useRouter } from "vue-router";
import Swal from "sweetalert2";
import * as yup from "yup";
import { useField, useForm, ErrorMessage } from "vee-validate";


const schema = yup.object({
    name: yup.string().required("Nama wajib diisi"),
    user_name: yup.string().required("Nama tidak boleh kosong"),
    phone: yup.string().matches(/^(\+62|62)?[\s-]?0?8[1-9]{1}\d{1}[\s-]?\d{4}[\s-]?\d{2,5}$/, "Nomor telpon tidak valid").required("Nomor telpon wajib diisi"),
    email: yup.string().required("Email tidak boleh kosong").email("Alamat email tidak valid"),
    password: yup.string().required("Password wajib diisi").matches(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{6,}$/, "Minimal 6 karakter, satu huruf besar dan satu angka"),
});

const {handleSubmit, errors, setFieldValue} = useForm({
    validationSchema: schema
});

const { value: name } = useField("name")
const { value: userName } = useField("user_name")
const { value: phone } = useField("phone")
const { value: email } = useField("email")
const { value: password } = useField("password")

const isLoading = ref(false)
const message = ref("")


const router = useRouter();

const handleRegist = handleSubmit( async (formData) => {
    isLoading.value = true;

    try {
        const submitForm = new FormData();
        
        submitForm.append("name", formData.name);
        submitForm.append("user_name", formData.user_name);
        submitForm.append("phone", formData.phone);
        submitForm.append("email", formData.email);
        submitForm.append("password", formData.password);

        const response = await api.post("/register", submitForm)
        Swal.fire({
            title: "Registrasi berhasil",
            text: "Silahkan login dengan akun anda",
            timer: 1500,
            icon: "success",
            position: "top-end",

        });

        router.push("/auth/login")
    } catch (error) {
        console.error("error", error)
    }
})

</script>

<template>
    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col d-flex justify-content-center">
                <div class="border border-1 border-black w-50 rounded">
                    <!-- <h4 class=" d-flex justify-content-center mt-4">Regist</h4> -->
                    <form @submit.prevent="handleRegist" class="p-4">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input
                                v-model="name"
                                type="text"
                                class="form-control"
                                id="name"
                                name="name"
                                :class="{
                                    'is-invalid' : errors.name
                                }"
                            />
                            <div v-if="errors.name" class=" invalid-feedback">
                                <ErrorMessage name="name" />
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="user-name" class="form-label"
                                >Nama Pengguna</label
                            >
                            <input
                                v-model="userName"
                                type="text"
                                class="form-control"
                                id="user-name"
                                name="user_name"
                                :class="{
                                    'is-invalid' : errors.user_name
                                }"
                            />
                            <div v-if="errors.user_name" class=" invalid-feedback">
                                <ErrorMessage name="user_name" />
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label"
                                >Nomor Handphone</label
                            >
                            <input
                                v-model="phone"
                                type="string"
                                class="form-control"
                                id="phone"
                                name="phone"
                                :class="{
                                    'is-invalid' : errors.phone
                                }"
                            />
                            <div v-if="errors.phone" class="invalid-feedback">
                                <ErrorMessage name="phone" />
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">
                                Email address
                            </label>
                            <input
                                v-model="email"
                                type="email"
                                class="form-control"
                                id="email"
                                aria-describedby="emailHelp"
                                name="email"
                                :class="{
                                    'is-invalid' : errors.email
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
                            <label
                                for="password"
                                class="form-label"
                                >Password</label
                            >
                            <input
                                v-model="password"
                                type="password"
                                class="form-control"
                                id="password"
                                name="password"
                                :class="{
                                    'is-invalid' : errors.password
                                }"
                            />
                            <div v-if="errors.password">
                                <ErrorMessage name="password" />
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <span v-if="isLoading" class=" spinner-border spinner-border-sm"></span>
                            <span v-else-if="!isLoading">Registrasi</span>
                        </button>
                        <router-link to="/">
                            <button
                                class="btn btn-primary w-100 mt-3"
                            >
                                Kembali ke beranda
                            </button>
                        </router-link>
                    </form>
                    <div class="d-flex justify-content-center">
                        <p>
                            Sudah punya akun? Silahkan Masuk
                            <router-link to="/auth/login">Di sini</router-link>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
