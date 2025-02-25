<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";

const form = useForm({
    latitude: "",
    longitude: "",
});

const getLocation = () => {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition((position) => {
            form.latitude = position.coords.latitude;
            form.longitude = position.coords.longitude;
        }, (error) => {
            console.error("Error getting location:", error);
        });
    } else {
        console.error("Geolocation is not supported by this browser.");
    }
};
</script>

<template>
    <div>
        <h2>Absensi Kehadiran</h2>
        <button @click="getLocation">Ambil Lokasi</button>
        <form @submit.prevent="form.post(route('attendance.store'))">
            <input type="text" v-model="form.latitude" placeholder="Latitude" readonly />
            <input type="text" v-model="form.longitude" placeholder="Longitude" readonly />
            <button class="bg-blue-500 font-semibold p-3" type="submit">Absen Sekarang</button>
        </form>
    </div>
</template>
