<script setup>
import { usePage } from "@inertiajs/vue3";
import DashboardAdmin from "../Components/DashboardAdmin.vue";
import DashboardEmployee from "../Components/DashboardEmployee.vue";

const page = usePage();
const role = page.props.user.data.role; // "admin" atau "employee"
</script>

<template>
    <DashboardEmployee v-if="role === 'employee'" />
    <DashboardAdmin v-else-if="role === 'admin'" />
</template>
