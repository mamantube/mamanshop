<script setup>
import Swal from 'sweetalert2';

const sendMessage = () => {
    Swal.fire("Pesan terkirim", "", "success")
}

</script>

<template>
    <div class=" justify-content-center align-items-center vh-100 mt-5">
        <div class="row">
            <div class="col d-flex justify-content-center align-items-center">
                <div class=" card contact_card mx-3">
                    <div class=" card-title d-flex justify-content-center align-items-center pt-5">
                        <h3>Hubungi Kami</h3>
                    </div>
                    <div class=" card-body">
                        <!-- <form > -->
                            <div class=" input-group mb-3">
                                <input type="text" class=" form-control border-2" required placeholder="Masukkan alamat email">
                            </div>
                            <div class=" input-group mb-3">
                                <input type="number" class=" form-control border-2" placeholder="Masukkan nomor telpon">
                            </div>
                            <div class=" input-group mb-3">
                                <textarea class=" form-control border-2" rows="4" placeholder="Apa yang bisa kami bantu?" name="" id=""></textarea>
                            </div>
                            <button @click="sendMessage" type="button" class=" btn btn-primary w-25 mb-4">Kirim</button>
                        <!-- </form> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>