<script setup>
import { Link, router, usePage } from "@inertiajs/vue3";
import { computed, ref } from "vue";

const page = usePage();
const isLoggedIn = computed(() => page.props.user);
const userName = computed(() => page.props.user.data.name);
const userEmail = computed(() => page.props.user.data.email);
const userAvatar = computed(() => page.props.user.data.avatar_url);
const userRole = computed(() => page.props.user.data.role);

const isDropdownOpen = ref(false);

const toggleDropdown = () => {
    isDropdownOpen.value = !isDropdownOpen.value;
};

const logout = () => {
    router.post(route("logout"));
};
</script>

<template>
    <header class="bg-white shadow-md p-4 flex justify-between items-center">
        <h1 class="text-lg font-bold">
            {{ userRole === "admin" ? "Admin" : "Employee" }} Panel
        </h1>

        <!-- User Profile Dropdown -->
        <div class="relative" v-if="isLoggedIn">
            <button
                @click.prevent="toggleDropdown"
                class="flex items-center space-x-3 p-2 rounded-lg cursor-pointer hover:bg-purple-100"
            >
                <img
                    :src="userAvatar"
                    alt="User Avatar"
                    class="w-10 h-10 rounded-full"
                />
                <div class="hidden md:block text-left">
                    <p class="text-sm font-medium">{{ userName }}</p>
                    <p class="text-xs text-gray-600">{{ userEmail }}</p>
                </div>
            </button>

            <!-- Dropdown Menu -->
            <div
                v-if="isDropdownOpen"
                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg overflow-hidden border border-slate-200"
            >
                <Link
                    :href="route('profile.index')"
                    class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100 cursor-pointer"
                    >Profile</Link
                >
                <a
                    href="#"
                    class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100"
                    >Settings</a
                >
                <hr />
                <button
                    @click="logout"
                    class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-purple-100 cursor-pointer"
                >
                    Logout
                </button>
            </div>
        </div>
    </header>
</template>

<style scoped></style>
