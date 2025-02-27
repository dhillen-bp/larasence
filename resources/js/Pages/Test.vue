<script setup>
import AppLayout from "../Layouts/AppLayout.vue";
import { toast } from "vue3-toastify";

const notify = () => {
    toast("Wow so easy !", {
        autoClose: 1000,
        position: toast.POSITION.TOP_RIGHT,
    });
};
</script>

<template>
    <AppLayout>
        <h1>Test</h1>

        <div><button @click="notify">Notify !</button></div>
    </AppLayout>
</template>

<style scoped></style>
