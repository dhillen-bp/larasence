<script setup>
import { Link, router, usePage } from "@inertiajs/vue3";
import AppLayout from "../../../Layouts/AppLayout.vue";
import { toast } from "vue3-toastify";
import { showToastSuccess } from "../../../Composables/useToast";
import Pagination from "../../../Components/Pagination.vue";
import { ref, watch } from "vue";
import debounce from "lodash/debounce";

defineProps({
    attendances: {
        type: Object,
        required: true,
    },
    filters: {
        type: Object,
        default: () => ({}),
    },
});

const search = ref("");
const page = usePage();
const isLoading = ref(false);

const deleteEmployee = (id) => {
    if (confirm("Are you sure you want to delete this attendance?")) {
        router.delete(route("admin.attendances.destroy", id), {
            onSuccess: () => {
                showToastSuccess(page.props.flash.success);
            },
        });
    }
};

const searchAttendance = debounce((query) => {
    isLoading.value = true;
    router.get(
        route("admin.attendances.index"),
        { search: query },
        {
            preserveState: true,
            replace: true,
            onFinish: () => {
                isLoading.value = false;
            },
        }
    );
}, 1000);

watch(search, (newValue) => {
    searchAttendance(newValue);
});
</script>

<template>
    <AppLayout>
        <div class="overflow-x-auto">
            <div class="mb-6">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-bold">List Attendance</h1>
                </div>
                <form @submit.prevent class="flex space-x-2">
                    <input
                        v-model="search"
                        type="text"
                        placeholder="Search..."
                        class="px-3 py-2 border rounded-lg text-sm"
                    />
                </form>

                <div
                    v-if="isLoading"
                    class="text-purple-600 font-semibold text-sm my-2"
                >
                    Loading...
                </div>

                <hr class="mt-1 border-purple-500" />
            </div>

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
                        <th
                            class="whitespace-nowrap px-4 py-2 font-medium text-slate-900"
                        >
                            Action
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
                        <td class="whitespace-nowrap px-4 py-2 text-slate-700">
                            {{ attendance.formatted_status }}
                        </td>
                        <td
                            class="whitespace-nowrap px-4 py-2 space-x-3 text-slate-700 font-medium"
                        >
                            <Link
                                :href="
                                    route(
                                        'admin.attendances.edit',
                                        attendance.id
                                    )
                                "
                                class="px-3 py-1 text-sm bg-amber-500 text-slate-100 rounded-full"
                                >Edit</Link
                            >
                            <button
                                @click="deleteEmployee(attendance.id)"
                                class="px-3 py-1 text-sm bg-red-500 text-slate-100 rounded-full cursor-pointer"
                            >
                                Delete
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <Pagination :meta="attendances.meta" />
        </div>
    </AppLayout>
</template>

<style scoped></style>
