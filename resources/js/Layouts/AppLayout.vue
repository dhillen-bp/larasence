<script setup>
import { ref } from "vue";
import Sidebar from "../Components/Sidebar.vue";
import Navbar from "../Components/Navbar.vue";

const isSidebarOpen = ref(false);
</script>

<template>
    <div class="flex min-h-screen bg-purple-50">
        <!-- Sidebar -->
        <Sidebar
            :is-open="isSidebarOpen"
            class="fixed top-0 z-20 min-h-screen transition-transform duration-300 overflow-y-hidden"
        />

        <!-- Overlay for small screens -->
        <div
            v-if="isSidebarOpen"
            @click="isSidebarOpen = false"
            class="fixed inset-0 bg-black bg-opacity-50 z-10 md:hidden"
        ></div>

        <!-- Main Content -->
        <div class="flex flex-1 flex-col min-h-screen">
            <!-- Navbar -->
            <Navbar @toggle-sidebar="isSidebarOpen = !isSidebarOpen" />

            <!-- Content -->
            <main class="flex-1 p-6 overflow-auto mt-8">
                <slot />
            </main>
        </div>
    </div>
</template>
