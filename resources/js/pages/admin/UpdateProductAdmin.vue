<script setup>
import { useRoute, useRouter } from "vue-router";
import { ref, onMounted, computed, nextTick } from "vue";
import api from "../../api";
import Swal from "sweetalert2";
import * as yup from "yup";
import { useForm, useField, ErrorMessage } from "vee-validate";

const route = useRoute();
const router = useRouter();
const product = ref({});
const loading = ref(false);
let token = localStorage.getItem("token");
const id = ref(null);

const schema = yup.object({
    product_name: yup.string().required("Nama produk wajib diisi"),
    stock: yup
        .number()
        .required("Stock tidak boleh kosong")
        .min(0, "Stock minimal 0"),
    price: yup
        .number()
        .required("Harga produk tidak boleh kosong")
        .min(0, "Harga produk tidak boleh bernilai negatif"),
    description: yup.string().required("Deskripsi produk wajib diisi"),
    category: yup.string().required("Category tidak boleh kosong"),
    image: yup.mixed().nullable(),
});

const { handleSubmit, errors, values, setFieldValue, setValues } = useForm({
    validationSchema: schema, initialValues: {
        product_name: "",
        stock: "",
        price: "",
        description: "",
        category: "",
        image: null,
    }
});

const { value: productName } = useField("product_name");
const { value: stock } = useField("stock");
const { value: price } = useField("price");
const { value: description } = useField("description");
const { value: category } = useField("category");
const { value: imageField } = useField("image");

const previewImage = computed(() => {
    if (imageField.value instanceof File) {
        return URL.createObjectURL(imageField.value);
    } else if (typeof imageField.value === "string") {
        return imageField.value;
    }
    return null;
});

// const handleFileChange = (e) => {
//     image.value = e.target.files[0];
// }

const handleFileChange = (e) => {
    const file = e.target.files[0];
    if (file) imageField.value = file;
};

const getProduct = async () => {
    try {
        const response = await api.get(`products/${route.params.id}`);
        product.value = response.data.data;
        id.value = response.data.data.id;

        await nextTick()

            setValues({
                product_name: product.value.product_name,
                stock: product.value.stock,
                price: product.value.price,
                description: product.value.description,
                category: product.value.category,
                image: product.value.image,
            });
            console.log(product);
        
    } catch (error) {
        Swal.fire("Gagal memuat data", "", "error");
        router.push("/admin/products");
        console.error(error);
    }
};

onMounted(getProduct);

const updateData = handleSubmit(async (formValues) => {
    loading.value = true;

    try {
        const formData = new FormData();

        formData.append("product_name", formValues.product_name);
        formData.append("stock", formValues.stock);
        formData.append("price", formValues.price);
        formData.append("description", formValues.description);
        formData.append("category", formValues.category);

        if (formValues.image instanceof File) {
            formData.append("image", formValues.image);
        }
        const result = await Swal.fire({
            title: "Apakah anda ingin menyimpan perubahan data?",
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: "Simpan",
            denyButtonText: "Jangan simpan",
        });

        if (result.isConfirmed) {
            await api.post(`products/update/${route.params.id}`, formData, {
                headers: {
                    Authorization: `Bearer ${token}`,
                    "Content-Type": "multipart/form-data",
                },
            });
            Swal.fire("Data produk diperbaharui", "", "success", 3000);
            router.push(`/admin/products/${id.value}`);
        } else if (result.isDenied) {
            Swal.fire("Batal memperbaharui data", "", "info", 300);
            router.push("admin/products")
        }
    } catch (error) {
        Swal.fire("error", `${error}`, "error", 3000);
    }
});
</script>

<template>
    <div class="container py-5">
        <div class="row">
            <div class="col md-8">
                <div class="card shadow-sm">
                    <div
                        class="card-header bg-secondary text-white text-center"
                    >
                        <h1>UPDATE PRODUK</h1>
                    </div>
                    <div class="card-body">
                        <div v-if="loading" class="text-center">
                            <div
                                class="spinner-border text-primary"
                                role="status"
                            >
                                <span class="visually-hidden"
                                    >Memuat product....</span
                                >
                            </div>
                        </div>
                        <div v-else>
                            <form @submit.prevent="updateData">
                                <div class="mb-3">
                                    <label for="product_name" class="form-label"
                                        >Nama produk</label
                                    >
                                    <input
                                        v-model="productName"
                                        type="text"
                                        class="form-control"
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
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Stock</label>
                                    <input
                                        v-model="stock"
                                        type="number"
                                        class="form-control"
                                        :class="{ 'is-invalid': errors.stock }"
                                    />
                                    <ErrorMessage
                                        name="stock"
                                        class="invalid-feedback"
                                    />
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Harga</label>
                                    <input
                                        v-model="price"
                                        type="number"
                                        class="form-control"
                                        :class="{ 'is-invalid': errors.price }"
                                    />
                                    <ErrorMessage
                                        name="price"
                                        class="invalid-feedback"
                                    />
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Deskripsi</label>
                                    <textarea
                                        v-model="description"
                                        class="form-control"
                                        :class="{
                                            'is-invalid': errors.description,
                                        }"
                                    ></textarea>
                                    <ErrorMessage
                                        name="description"
                                        class="invalid-feedback"
                                    />
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Kategori</label>
                                    <select v-model="category" class=" form-select" name="category" id="category" :class="{'is-invalid' : errors.category }">
                                        <option value="" disabled selected>Pilih kategori</option>
                                        <option value="men">Pria</option>
                                        <option value="women">Wanita</option>
                                    </select>
                
                                    <ErrorMessage
                                        name="category"
                                        class="invalid-feedback"
                                    />
                                </div>

                                <div class="mb-3">
                                    <label class="form-label"
                                        >Gambar Produk</label
                                    >
                                    <input
                                        type="file"
                                        @change="handleFileChange"
                                        class="form-control"
                                    />
                                    <img
                                        v-if="previewImage"
                                        :src="previewImage"
                                        class="img-thumbnail mt-2"
                                        width="150"
                                    />
                                </div>
                                <button class=" btn btn-outline-primary"> Simpan perubahan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
