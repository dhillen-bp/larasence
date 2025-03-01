<script setup>
import { useLocation } from "../../../Composables/useLocation";
import AppLayout from "../../../Layouts/AppLayout.vue";
import { watchEffect } from "vue";
import { usePage } from "@inertiajs/vue3";
import { toast } from "vue3-toastify";

defineProps({
    attendances: {
        type: Object,
        required: true,
    },
});

const { checkIn, checkOut } = useLocation();

const page = usePage();
// console.log("flash msg: ", page.props.flash.error);
</script>

<template>
    <AppLayout>
        <div>
            <h1 class="text-2xl font-bold mb-4 underline">Absence Action</h1>
            <div class="flex justify-between">
                <button
                    @click="checkIn"
                    class="bg-green-500 text-white px-4 py-2 rounded cursor-pointer"
                >
                    Check In
                </button>
                <button
                    @click="checkOut"
                    class="bg-amber-500 text-white px-4 py-2 rounded cursor-pointer"
                >
                    Check Out
                </button>
            </div>
        </div>

        <hr class="my-6 border-purple-500" />

        <div class="overflow-x-auto">
            <h1 class="text-2xl font-bold mb-4 underline">Absence History</h1>

            <table
                class="min-w-full divide-y-2 divide-purple-200 bg-purple-50 text-sm"
            >
                <thead class="ltr:text-left rtl:text-right">
                    <tr>
                        <th
                            class="whitespace-nowrap px-4 py-2 font-medium text-slate-900"
                        >
                            Name
                        </th>
                        <th
                            class="whitespace-nowrap px-4 py-2 font-medium text-slate-900"
                        >
                            Check In
                        </th>
                        <th
                            class="whitespace-nowrap px-4 py-2 font-medium text-slate-900"
                        >
                            Check Out
                        </th>
                        <th
                            class="whitespace-nowrap px-4 py-2 font-medium text-slate-900"
                        >
                            Status
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-purple-200">
                    <tr
                        v-for="attendance in attendances.data"
                        :key="attendance.id"
                        class="odd:bg-purple-50"
                    >
                        <td class="whitespace-nowrap px-4 py-2 text-slate-900">
                            {{ attendance.user.name }}
                        </td>
                        <td class="whitespace-nowrap px-4 py-2 text-slate-700">
                            {{ attendance.check_in }}
                        </td>
                        <td class="whitespace-nowrap px-4 py-2 text-slate-700">
                            {{ attendance.check_out }}
                        </td>
                        <td
                            class="whitespace-nowrap px-4 py-2 text-slate-700 font-medium"
                        >
                            {{ attendance.formatted_status }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AppLayout>
</template>
