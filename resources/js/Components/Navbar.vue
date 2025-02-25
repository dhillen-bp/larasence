<script setup>
import { ref } from "vue";

const user = ref({
    name: "Eric Frusciante",
    email: "eric@frusciante.com",
});

const isDropdownOpen = ref(false);

const toggleDropdown = () => {
    isDropdownOpen.value = !isDropdownOpen.value;
};
</script>

<template>
    <header class="bg-white shadow-md p-4 flex justify-between items-center">
        <h1 class="text-lg font-bold">Dashboard</h1>

        <!-- User Profile Dropdown -->
        <div class="relative">
            <button
                @click.prevent="toggleDropdown"
                class="flex items-center space-x-3 p-2 rounded-lg cursor-pointer hover:bg-purple-100"
            >
                <img
                    src="https://ui-avatars.com/api/?name=Eric+Frusciante&background=random"
                    alt="User Avatar"
                    class="w-10 h-10 rounded-full"
                />
                <div class="hidden md:block text-left">
                    <p class="text-sm font-medium">{{ user.name }}</p>
                    <p class="text-xs text-gray-600">{{ user.email }}</p>
                </div>
            </button>

            <!-- Dropdown Menu -->
            <div
                v-if="isDropdownOpen"
                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg overflow-hidden border border-slate-200"
            >
                <a
                    href="#"
                    class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100"
                    >Profile</a
                >
                <a
                    href="#"
                    class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100"
                    >Settings</a
                >
                <hr />
                <button
                    class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-purple-100 cursor-pointer"
                >
                    Logout
                </button>
            </div>
        </div>
    </header>
</template>

<style scoped></style>
