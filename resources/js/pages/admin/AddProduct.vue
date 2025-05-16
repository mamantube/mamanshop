<script setup>
import api from "../../api";
import { ref, computed } from "vue";
import { useRouter } from "vue-router";
import { useForm, useField, ErrorMessage } from "vee-validate";
import * as yup from "yup";
import Swal from "sweetalert2";

const router = useRouter();

const schema = yup.object({
    product_name: yup.string().required("Nama produk harus diisi"),
    stock: yup
        .number()
        .required("Stok wajib diisi")
        .min(0, "Stok tidak boleh bernilai negatif"),
    price: yup
        .number()
        .required("Harga tidak boleh kosong")
        .min(0, "Harga tidak boleh bernilai negatif"),
    description: yup.string().required("Descripsi harus diisi"),
    category: yup.string().required("String harus diisi"),
    image: yup
        .mixed()
        .required("Gambar tidak boleh kosong")
        .test("filetype", "Format gambar harus JPG, JEPG, PNG", (value) => {
            if (!value) return false;
            return ["image/jpg", "image/jpeg", "image/png"].includes(
                value.type
            );
        })
        .test("filesize", "Ukuran gambar maksimal 2MB", (value) => {
            if (!value) return false;
            return value.size <= 2 * 1024 * 1024;
        }),
});

const { handleSubmit, errors, resetForm, setFieldValue } = useForm({
    validationSchema: schema,
});

const { value: productName } = useField("product_name");
const { value: stock } = useField("stock");
const { value: price } = useField("price");
const { value: description } = useField("description");
const { value: category } = useField("category");
const { value: image } = useField("image");

const previewImage = computed(() => {
    if (image && image instanceof File) {
        return URL.createObjectURL(values.image);
    }
    return null;
});

const isLoading = ref(false);
const message = ref("");

const handleFileChange = (event) => {
    const file = event.target.files[0];
    setFieldValue("image", file);
};

const addProduct = handleSubmit(async (formData) => {
    isLoading.value = true;
    message.value = "";

    try {
        const submitForm = new FormData();

        submitForm.append("product_name", formData.product_name);
        submitForm.append("stock", formData.stock);
        submitForm.append("price", formData.price);
        submitForm.append("description", formData.description);
        submitForm.append("category", formData.category);
        submitForm.append("image", formData.image);

        const token = localStorage.getItem("token");

        await api.post("products/add", submitForm, {
            headers: {
                "Content-Type": "multipart/form-data",
                "Authorization" : `Bearer ${token}`,
            },
        });

        Swal.fire({
            title: "Berhasil!",
            text: "Produk berhasil ditambahkan",
            icon: "success",
            timer: 2000,
            showConfirmButton: false,
        });

        message.value = "Produk berhasil ditambahakan";
        setTimeout(() => {
            router.push("/admin/products");
        }, 3000);
    } catch (error) {
        console.error(error);
        Swal.fire({
            title: "Gagal",
            text: "Produk gagal ditambahkan",
            icon: "error",
            timer: 2000,
            confirmButtonText: "ok",
        });
    } finally {
        isLoading.value = false;
    }
});
</script>

<template>
    <div class="container py-5">
        <div class="row ">
            <div class="col md-8 d-flex justify-content-center text-xl-center">
                <div class="card shadow-sm w-75">
                    <div class="card-header bg-primary text-white">
                        <h1>Tambah Produk</h1>
                    </div>
                    <div class="card-body">
                        <form @submit.prevent="addProduct">
                            <div class="mb-3 mx-5">
                                <label for="product_name" class="form-label">
                                    Nama Produk
                                    <span class="text-danger">*</span>
                                </label>
                                <input
                                    v-model="productName"
                                    type="text"
                                    class="form-control"
                                    id="product_name"
                                    name="product_name"
                                    :class="{
                                        'is-invalid': errors.product_name,
                                    }"
                                />
                                <div
                                    v-if="errors.product_name"
                                    class="invalid-feedback"
                                >
                                    <ErrorMessage name="product_name" />
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="stock" class="form-label">
                                            Stock
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input
                                            v-model.number="stock"
                                            type="number"
                                            min="0"
                                            class="form-control"
                                            id="stock"
                                            name="stock"
                                            :class="{
                                                'is-invalid': errors.stock,
                                            }"
                                        />
                                        <div
                                            v-if="errors.stock"
                                            class="invalid-feedback"
                                        >
                                            <ErrorMessage name="stock" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="price" class="form-label">
                                            Harga
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input
                                            v-model.number="price"
                                            type="number"
                                            id="price"
                                            class="form-control"
                                            name="price"
                                            :class="{
                                                'is-invalid': errors.price,
                                            }"
                                        />
                                        <div
                                            v-if="errors.price"
                                            class="invalid-feedback"
                                        >
                                            <ErrorMessage name="price" />
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="category" class="form-label">
                                        Kategori
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select
                                        v-model="category"
                                        class="form-select"
                                        id="category"
                                        name="category"
                                        :class="{
                                            'is-invalid': errors.category,
                                        }"
                                    >
                                        <option value="" disabled selected>
                                            Pilih Kategori
                                        </option>
                                        <option value="men">Pria</option>
                                        <option value="women">Wanita</option>
                                    </select>
                                    <div
                                        v-if="errors.category"
                                        class="invalid-feedback"
                                    >
                                        <ErrorMessage name="category" />
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">
                                        Description
                                        <span class="text-danger">*</span>
                                    </label>
                                    <textarea
                                        v-model="description"
                                        class="form-control"
                                        name="description"
                                        id="description"
                                        :class="{
                                            'is-invalid': errors.description,
                                        }"
                                    ></textarea>
                                    <div
                                        v-if="errors.description"
                                        class="invalid-feedback"
                                    >
                                        <ErrorMessage name="description" />
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label for="image" class="form-label">
                                        Gambar Produk
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input
                                        type="file"
                                        class="form-control"
                                        id="image"
                                        name="image"
                                        accept="image/jpeg, image/png, image/jpg"
                                        @change="handleFileChange"
                                        :class="{ 'is-invalid': errors.image }"
                                    />
                                    <div
                                        v-if="errors.image"
                                        class="invalid-feedback"
                                    >
                                        <ErrorMessage name="image" />
                                    </div>
                                    <small class="text-muted"
                                        >Format: JPEG, JPG, PNG (Maksimal
                                        2MB)</small
                                    >
                                    <div v-if="previewImage" class="mt-3">
                                        <img
                                            :src="previewImage"
                                            alt="Preview"
                                            class="img-thumbnail"
                                            style="max-height: 200px"
                                        />
                                    </div>
                                </div>
                                <div class="d-flex gap-2 flex-col">
                                    <button
                                        type="submit"
                                        class="btn btn-primary"
                                        :disabled="isLoading"
                                    >
                                        <span
                                            v-if="isLoading"
                                            class="spinner-border spinner-border-sm"
                                            role="status"
                                            aria-hidden="true"
                                        ></span>
                                        {{
                                            isLoading
                                                ? "Menyimpan..."
                                                : "Simpan Produk"
                                        }}
                                    </button>
                                    <router-link
                                        to="/admin/products"
                                        class="btn btn-outline-secondary"
                                    >
                                        Kembali
                                    </router-link>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.card {
    border-radius: 10px;
}

.card-header {
    border-radius: 10px 10px 0 0 !important;
}

.form-label {
    font-weight: 500;
}

.img-thumbnail {
    max-width: 100%;
    height: auto;
}
</style>
