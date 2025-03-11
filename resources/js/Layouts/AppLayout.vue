<script setup>
import Sidebar from "../Components/Sidebar.vue";
import Navbar from "../Components/Navbar.vue";
</script>

<template>
    <div class="flex min-h-screen bg-purple-50">
        <!-- Sidebar -->
        <Sidebar
            class="fixed top-0 flex flex-col min-h-screen bg-purple-400 z-2"
        />

        <!-- Main Content -->
        <div class="flex flex-1 flex-col min-h-screen">
            <!-- Navbar -->
            <Navbar class="fixed top-0 w-full z-1" />

            <!-- Content -->
            <main class="flex-1 p-6 overflow-auto mt-24 md:ml-32 lg:ml-60">
                <slot />
            </main>
        </div>
    </div>
</template>
