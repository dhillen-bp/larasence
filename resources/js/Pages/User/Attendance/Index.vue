<script setup>
import { useLocation } from "../../../Composables/useLocation";
import AppLayout from "../../../Layouts/AppLayout.vue";
import { ref, watch, watchEffect } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import { toast } from "vue3-toastify";
import { debounce } from "lodash";

const props = defineProps({
    attendances: {
        type: Object,
        required: true,
    },
    filters: {
        type: Object,
        default: () => ({}),
    },
});

const { checkIn, checkOut } = useLocation();

const page = usePage();
const date = ref(props.filters.date || "");
const status = ref(props.filters.status || "");
const isLoading = ref(false);

const resetFilters = () => {
    date.value = "";
    status.value = "";
    searchAttendance();
};

const searchAttendance = debounce(() => {
    isLoading.value = true;
    router.get(
        route("attendances.index"),
        { date: date.value, status: status.value },
        {
            preserveState: true,
            replace: true,
            onFinish: () => {
                isLoading.value = false;
            },
        }
    );
}, 1000);

watch([date, status], searchAttendance);
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
            <form @submit.prevent class="flex space-x-2 justify-around my-2">
                <div class="space-x-2">
                    <label for="search" class="text-sm">Filter by Date:</label>
                    <input
                        v-model="date"
                        type="date"
                        class="px-3 py-2 border rounded-lg text-sm border-purple-200 focus:border-purple-600 focus:outline-none focus:ring-0"
                    />
                </div>
                <div class="space-x-2">
                    <label for="status" class="text-sm"
                        >Filter by Status:</label
                    >
                    <select
                        id="status"
                        v-model="status"
                        class="px-3 py-2 border rounded-lg text-sm border-purple-200 focus:border-purple-600 focus:outline-none focus:ring-0"
                    >
                        <option value="">All</option>
                        <option value="on_time">On Time</option>
                        <option value="late">Late</option>
                        <option value="pending">Pending</option>
                        <option value="absent">Absent</option>
                    </select>
                </div>
                <button
                    @click.prevent="resetFilters"
                    class="px-3 py-1.5 bg-slate-400 text-white rounded-lg text-sm hover:bg-slate-500"
                >
                    Reset Filter
                </button>
            </form>

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
