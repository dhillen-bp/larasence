<script setup>
import { Link, router, usePage } from "@inertiajs/vue3";
import AppLayout from "../../../Layouts/AppLayout.vue";
import { toast } from "vue3-toastify";
import { showToastSuccess } from "../../../Composables/useToast";

defineProps({
    employees: {
        type: Object,
        required: true,
    },
});

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
</script>

<template>
    <AppLayout>
        <div class="overflow-x-auto">
            <div class="mb-6">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-bold">List Employee</h1>
                    <Link
                        class="bg-purple-500 px-3 py-2 rounded-xl text-sm text-slate-100 cursor-pointer"
                        :href="route('admin.employees.create')"
                    >
                        Add Employee
                    </Link>
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
                        v-for="employee in employees"
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
        </div>
    </AppLayout>
</template>

<style scoped></style>
