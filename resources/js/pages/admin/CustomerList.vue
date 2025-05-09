<script setup>
import Swal from "sweetalert2";
import api from "../../api";
import { ref, onMounted } from "vue";

const listCust = ref([]);
const isLoading = ref(false);
const token = localStorage.getItem("token");

const getCustomer = async () => {
    isLoading.value = true;
    try {
        const response = await api.get("users", {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        });
        listCust.value = response.data.data;

        console.log(listCust);
    } catch (error) {
        console.error(error);
    }
};

onMounted(getCustomer);

const deleteCust = async (id) => {
    const result = Swal.fire({
        title: "Apakah anda yakin ingin menghapus customer ini?",
        confirmButtonText: "Ya, hapus",
        showCancelButton: true,
        icon: "question",
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
    });

    if ((await result).isConfirmed) {
        await api.delete(`users/${id}`, {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        });
        await Swal.fire("Akun customer berhasil dihapus", "", "success");
        getCustomer();
    } else if ((await result).isDismissed) {
        Swal.fire("Akun batal dihapus", "", "info")
    }
};
</script>

<template>
    <div class="row vh-100">
        <div class="col">
            <table class="table table-striped">
                <thead>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Nomor Telpon</th>
                    <th>Aksi</th>
                </thead>
                <tbody>
                    <tr v-for="customer in listCust" :key="customer.id">
                        <td>{{ customer.name }}</td>
                        <td>{{ customer.email }}</td>
                        <td>{{ customer.phone }}</td>
                        <td>
                            <button @click="deleteCust(customer.id)" class=" btn btn-outline-danger" :disabled="customer.role === 'admin'">
                                Hapus akun
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
