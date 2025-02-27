<script setup>
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <div
        class="flex h-screen flex-col justify-between border-e border-slate-100 bg-purple-400"
    >
        <div class="px-4 py-6">
            <span
                class="grid h-10 md:w-32 lg:w-52 place-content-center rounded-lg bg-purple-100 text-purple-600 font-bold"
            >
                Larasence
            </span>

            <hr class="border border-purple-700 opacity-10 mt-6" />

            <ul class="mt-6 space-y-1.5">
                <li>
                    <Link
                        :href="route('dashboard')"
                        class="block rounded-lg bg-purple-100 px-4 py-2 text-sm font-medium text-purple-700"
                    >
                        Dashboard
                    </Link>
                </li>

                <li>
                    <Link
                        :href="route('attendances.index')"
                        class="block rounded-lg px-4 py-2 text-sm font-medium text-purple-100 hover:bg-purple-100 hover:text-purple-700"
                    >
                        Attendance
                    </Link>
                </li>

                <li>
                    <details
                        class="group [&_summary::-webkit-details-marker]:hidden"
                    >
                        <summary
                            class="flex cursor-pointer items-center justify-between rounded-lg px-4 py-2 text-purple-100 hover:bg-purple-100 hover:text-purple-700"
                        >
                            <span class="text-sm font-medium">
                                Employee Management
                            </span>

                            <span
                                class="shrink-0 transition duration-300 group-open:-rotate-180"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="size-5"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </span>
                        </summary>

                        <ul class="mt-2 space-y-1 px-4">
                            <li>
                                <a
                                    href="#"
                                    class="block rounded-lg px-4 py-2 text-sm font-medium text-purple-100 hover:bg-purple-100 hover:text-purple-700"
                                >
                                    Employee Data
                                </a>
                            </li>

                            <li>
                                <a
                                    href="#"
                                    class="block rounded-lg px-4 py-2 text-sm font-medium text-purple-100 hover:bg-purple-100 hover:text-purple-700"
                                >
                                    Attendance Recap
                                </a>
                            </li>
                        </ul>
                    </details>
                </li>
            </ul>
        </div>
    </div>
</template>

<style scoped></style>
