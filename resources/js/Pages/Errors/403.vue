<script setup>
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <div class="flex h-screen items-center justify-center bg-gray-100">
        <div class="text-center">
            <h1 class="text-6xl font-bold text-purple-600">403</h1>
            <p class="mt-4 text-lg text-gray-700">
                Oops! You don't have permission to access this page.
            </p>
            <Link
                :href="route('dashboard')"
                class="mt-6 inline-block rounded bg-purple-600 px-6 py-3 text-white hover:bg-purple-700"
            >
                Back to Dashboard
            </Link>
        </div>
    </div>
</template>
