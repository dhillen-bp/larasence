<script setup>
import { Link, router, usePage } from "@inertiajs/vue3";
import AppLayout from "../../../Layouts/AppLayout.vue";
import { toast } from "vue3-toastify";
import { showToastSuccess } from "../../../Composables/useToast";
import Pagination from "../../../Components/Pagination.vue";
import { ref } from "vue";

const props = defineProps({
    employees: {
        type: Object,
        required: true,
    },
    filters: {
        type: Object,
        default: () => ({}),
    },
});

const search = ref(props.filters.search || "");

const page = usePage();

const deleteEmployee = (id) => {
    if (confirm("Are you sure you want to delete this employee?")) {
        router.delete(route("admin.employees.destroy", id), {
            onSuccess: () => {
                showToastSuccess(page.props.flash.success);
            },
        });
    }
};

const submitSearch = () => {
    router.get(
        route("admin.employees.index"),
        { search: search.value },
        { preserveState: true }
    );
};
</script>

<template>
    <AppLayout>
        <div class="overflow-x-auto">
            <div class="mb-6">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-bold">List Employee</h1>
                    <div class="flex space-x-2">
                        <form
                            @submit.prevent="submitSearch"
                            class="flex space-x-2"
                        >
                            <input
                                v-model="search"
                                type="text"
                                placeholder="Search..."
                                class="px-3 py-2 border rounded-lg text-sm"
                            />
                            <button
                                type="submit"
                                class="bg-blue-500 px-3 py-2 rounded-xl text-sm text-white cursor-pointer"
                            >
                                Search
                            </button>
                        </form>
                        <Link
                            class="bg-purple-500 px-3 py-2 rounded-xl text-sm text-slate-100 cursor-pointer"
                            :href="route('admin.employees.create')"
                        >
                            Add Employee
                        </Link>
                    </div>
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
                            Email
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
                        v-for="employee in employees.data"
                        :key="employee.id"
                        class="odd:bg-purple-50"
                    >
                        <td class="whitespace-nowrap px-4 py-2 text-slate-900">
                            {{ employee.name }}
                        </td>
                        <td class="whitespace-nowrap px-4 py-2 text-slate-700">
                            {{ employee.email }}
                        </td>
                        <td
                            class="whitespace-nowrap px-4 py-2 space-x-3 text-slate-700 font-medium"
                        >
                            <Link
                                :href="
                                    route('admin.employees.edit', employee.id)
                                "
                                class="px-3 py-1 text-sm bg-amber-500 text-slate-100 rounded-full"
                                >Edit</Link
                            >
                            <button
                                @click="deleteEmployee(employee.id)"
                                class="px-3 py-1 text-sm bg-red-500 text-slate-100 rounded-full cursor-pointer"
                            >
                                Delete
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <Pagination :meta="employees.meta" />
        </div>
    </AppLayout>
</template>

<style scoped></style>
