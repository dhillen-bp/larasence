<script setup>
import Sidebar from "../Components/Sidebar.vue";
import Navbar from "../Components/Navbar.vue";
</script>

<template>
    <div class="flex h-screen bg-purple-50">
        <!-- Sidebar -->
        <Sidebar />

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Navbar -->
            <Navbar />

            <!-- Content -->
            <main class="flex-1 p-6">
                <slot />
            </main>
        </div>
    </div>
</template>
