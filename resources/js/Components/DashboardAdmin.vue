<script setup>
import { ref, onMounted } from "vue";
import AppLayout from "../Layouts/AppLayout.vue";
import { usePage } from "@inertiajs/vue3";

const currentTime = ref("");
const page = usePage();

const totalEmployees = ref(page.props.totalEmployees);
const totalAttendance = ref(page.props.totalAttendance);
const totalPresentOnTime = ref(page.props.totalPresentOnTime);
const totalPresentLate = ref(page.props.totalPresentLate);
const totalAbsent = ref(page.props.totalAbsent);
const stillCheckIn = ref(page.props.stillCheckIn);
// const latestCheckIn = ref(page.props.latestCheckIn);

const props = defineProps({
    latestCheckIn: {
        type: Object,
        required: true,
    },
});
console.log("data dari props:", props.latestCheckIn);

onMounted(() => {
    setInterval(() => {
        const now = new Date();
        currentTime.value = now.toLocaleTimeString("id-ID", {
            hour: "2-digit",
            minute: "2-digit",
            second: "2-digit",
        });
    }, 1000);
});
</script>

<template>
    <AppLayout>
        <div
            class="flex flex-col items-center justify-center min-h-screen w-full"
        >
            <h1 class="text-2xl font-bold">
                Welcome, {{ page.props.user.data.name }}! 👨‍💼
            </h1>
            <p class="mt-2 text-lg text-gray-600">
                Current Time:
                <span class="font-semibold">{{ currentTime }}</span>
            </p>

            <div class="mt-6 w-full px-6 bg-purple-300 py-6">
                <h2 class="mb-6 text-slate-50 font-bold text-2xl text-center">
                    Today's Attendance Data
                </h2>
                <div class="grid grid-cols-3 gap-4 text-center">
                    <div
                        class="p-4 bg-purple-500 text-white rounded-lg col-span-2"
                    >
                        <p class="text-lg font-bold">Total Employees</p>
                        <span class="text-3xl font-semibold">{{
                            totalEmployees
                        }}</span>
                    </div>
                    <div class="p-4 bg-blue-500 text-white rounded-lg">
                        <p class="text-lg font-bold">Still Check-in</p>
                        <span class="text-3xl font-semibold">{{
                            stillCheckIn
                        }}</span>
                    </div>
                    <div class="p-4 bg-green-500 text-white rounded-lg">
                        <p class="text-lg font-bold">Total Present On Time</p>
                        <span class="text-3xl font-semibold">{{
                            totalPresentOnTime
                        }}</span>
                    </div>
                    <div class="p-4 bg-amber-500 text-white rounded-lg">
                        <p class="text-lg font-bold">Total Present Late</p>
                        <span class="text-3xl font-semibold">{{
                            totalPresentLate
                        }}</span>
                    </div>
                    <div class="p-4 bg-red-500 text-white rounded-lg">
                        <p class="text-lg font-bold">Total Absent</p>
                        <span class="text-3xl font-semibold">{{
                            totalAbsent
                        }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-6 w-full px-6">
                <h2 class="mb-2 text-xl font-bold text-gray-700 text-center">
                    Recent Attendance History
                </h2>
                <div>{{ latestCheckIn }}</div>
                <div class="bg-white p-4 rounded-lg shadow-lg">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="py-2 px-4 border">Name</th>
                                <th class="py-2 px-4 border">Check In</th>
                                <th class="py-2 px-4 border">Check Out</th>
                                <th class="py-2 px-4 border">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="attendance in latestCheckIn"
                                :key="attendance.id"
                            >
                                <td class="py-2 px-4 border text-center">
                                    {{ attendance.user.name }}
                                </td>
                                <td class="py-2 px-4 border text-center">
                                    {{ attendance.check_in }}
                                </td>
                                <td class="py-2 px-4 border text-center">
                                    {{ attendance.check_out }}
                                </td>
                                <td
                                    class="py-2 px-4 border text-center"
                                    :class="{
                                        'text-green-600 font-bold':
                                            attendance.status === 'Check-in',
                                        'text-red-600 font-bold':
                                            attendance.status === 'Check-out',
                                    }"
                                >
                                    {{ attendance.status }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
