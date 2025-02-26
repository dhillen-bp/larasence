<script setup>
import { Link, useForm } from "@inertiajs/vue3";

const form = useForm({
    email: "",
    password: "",
});
</script>

<template>
    <section class="bg-white">
        <div class="lg:grid lg:min-h-screen lg:grid-cols-12">
            <aside
                class="relative block h-16 lg:order-last lg:col-span-5 lg:h-full xl:col-span-6"
            >
                <img
                    alt=""
                    src="https://images.unsplash.com/photo-1605106702734-205df224ecce?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=870&q=80"
                    class="absolute inset-0 h-full w-full object-cover"
                />
            </aside>

            <main
                class="flex items-center justify-center px-8 py-8 sm:px-12 lg:col-span-7 lg:px-16 lg:py-12 xl:col-span-6"
            >
                <div class="max-w-xl lg:max-w-3xl">
                    <h1
                        class="mt-6 text-2xl font-bold text-gray-900 sm:text-3xl md:text-4xl"
                    >
                        Larasence
                    </h1>

                    <p class="mt-4 leading-relaxed text-gray-500">
                        Login to Larasence!
                    </p>

                    <form
                        class="mt-8 grid grid-cols-6 gap-6"
                        @submit.prevent="form.post(route('authenticate'))"
                    >
                        <div class="col-span-6">
                            <label
                                for="email"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Email
                            </label>

                            <input
                                v-model="form.email"
                                type="email"
                                id="email"
                                class="w-full rounded-lg border border-purple-200 bg-white py-2 pl-2 pr-4 text-sm text-gray-700 shadow-sm focus:outline-none focus:ring-0 focus:border-purple-500"
                                :class="{
                                    'invalid-feedback-input': form.errors.email,
                                }"
                            />
                            <div
                                class="invalid-feedback-text"
                                v-if="form.errors.email"
                                v-text="form.errors.email"
                            ></div>
                        </div>

                        <div class="col-span-6">
                            <label
                                for="password"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Password
                            </label>

                            <input
                                v-model="form.password"
                                type="password"
                                id="password"
                                class="w-full rounded-lg border border-purple-200 bg-white py-2 pl-2 pr-4 text-sm text-gray-700 shadow-sm focus:outline-none focus:ring-0 focus:border-purple-500"
                                :class="{
                                    'invalid-feedback-input':
                                        form.errors.password,
                                }"
                            />
                            <div
                                class="invalid-feedback-text"
                                v-if="form.errors.password"
                                v-text="form.errors.password"
                            ></div>
                        </div>

                        <div
                            class="col-span-6 sm:flex sm:items-center sm:gap-4"
                        >
                            <button
                                type="submit"
                                class="cursor-pointer inline-block shrink-0 rounded-md border border-purple-600 bg-purple-600 px-12 py-3 text-sm font-medium text-white transition hover:bg-transparent hover:text-purple-600 focus:ring-3 focus:outline-hidden"
                            >
                                Login
                            </button>

                            <p class="mt-4 text-sm text-gray-500 sm:mt-0">
                                Don;t have an account?
                                <Link
                                    :href="route('login')"
                                    class="text-gray-700 underline"
                                    >Register here!</Link
                                >.
                            </p>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </section>
</template>

<style scoped></style>
