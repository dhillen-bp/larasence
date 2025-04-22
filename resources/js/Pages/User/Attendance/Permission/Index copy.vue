<script setup>
import { Link } from "@inertiajs/vue3";
import AppLayout from "../../../../Layouts/AppLayout.vue";

defineProps({
    permissions: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <AppLayout>
        <h1 class="text-2xl font-bold mb-4 underline">Attendance Permission</h1>

        <table
            class="min-w-full divide-y-2 divide-purple-200 bg-purple-50 text-sm"
        >
            <thead class="ltr:text-left rtl:text-right">
                <tr>
                    <th
                        class="whitespace-nowrap px-4 py-2 font-medium text-slate-900"
                    >
                        No
                    </th>
                    <th
                        class="whitespace-nowrap px-4 py-2 font-medium text-slate-900"
                    >
                        Name
                    </th>
                    <th
                        class="whitespace-nowrap px-4 py-2 font-medium text-slate-900"
                    >
                        Type
                    </th>
                    <th
                        class="whitespace-nowrap px-4 py-2 font-medium text-slate-900"
                    >
                        Start Date - End Date
                    </th>
                    <th
                        class="whitespace-nowrap px-4 py-2 font-medium text-slate-900"
                    >
                        Is Approved
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
                    v-for="(permission, index) in permissions.data"
                    :key="permission.id"
                    class="odd:bg-purple-50"
                >
                    <td class="whitespace-nowrap px-4 py-2 text-slate-700">
                        {{
                            (permissions.meta.current_page - 1) *
                                permissions.meta.per_page +
                            index +
                            1
                        }}
                    </td>
                    <td class="whitespace-nowrap px-4 py-2 text-slate-700">
                        {{ permission.user.name }}
                    </td>
                    <td class="whitespace-nowrap px-4 py-2 text-slate-700">
                        {{ permission.type }}
                    </td>
                    <td class="whitespace-nowrap px-4 py-2 text-slate-700">
                        {{ permission.start_date }} <b>-</b>
                        {{ permission.end_date }}
                    </td>
                    <td class="whitespace-nowrap px-4 py-2 text-slate-700">
                        {{ permission.is_approved ? "Yes" : "No" }}
                    </td>
                    <td
                        class="whitespace-nowrap px-4 py-2 text-slate-700 font-medium"
                    >
                        <Link
                            :href="route('permission.show', permission.id)"
                            class="px-3 py-1 text-sm bg-purple-500 text-slate-100 rounded-full"
                            >Detail</Link
                        >
                    </td>
                </tr>
            </tbody>
        </table>
    </AppLayout>
</template>

<style scoped></style>
