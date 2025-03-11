<script setup>
import { ref, onMounted } from "vue";
import AppLayout from "../Layouts/AppLayout.vue";
import { Link, usePage } from "@inertiajs/vue3";

const currentTime = ref("");
const page = usePage();

onMounted(() => {
    setInterval(() => {
        const now = new Date();
        currentTime.value = now.toLocaleTimeString("id-ID", {
            hour: "2-digit",
            minute: "2-digit",
            second: "2-digit",
        });
    }, 1000);
});
</script>

<template>
    <AppLayout>
        <div class="flex flex-col items-center justify-center min-h-screen">
            <h1 class="text-2xl font-bold">
                Welcome, {{ page.props.user.data.name }}! 😊
            </h1>
            <p class="mt-2 text-lg text-gray-600">
                Current Time:
                <span class="font-semibold">{{ currentTime }}</span>
            </p>
            <Link
                :href="route('attendances.index')"
                class="mt-6 px-6 py-3 bg-blue-600 text-white rounded-lg shadow-lg hover:bg-blue-700"
            >
                Start Attendance
            </Link>
        </div>
    </AppLayout>
</template>
