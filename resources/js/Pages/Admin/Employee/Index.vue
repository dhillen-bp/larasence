<script setup>
import { Link, router, usePage } from "@inertiajs/vue3";
import AppLayout from "../../../Layouts/AppLayout.vue";
import { showToastSuccess } from "../../../Composables/useToast";
import Pagination from "../../../Components/Pagination.vue";
import { computed, onMounted, ref, watch } from "vue";
import { debounce } from "lodash";
import { Button, Column, DataTable } from "primevue";
import { FilterMatchMode } from "@primevue/core/api";
import IconField from "primevue/iconfield";
import InputIcon from "primevue/inputicon";
import InputText from "primevue/inputtext";

defineProps({
    employees: {
        type: Object,
        required: true,
    },
});
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
    name: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
    email: { value: null, matchMode: FilterMatchMode.STARTS_WITH },
});

const page = usePage();
const loading = ref(false);

// Fungsi hapus karyawan
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
            <div class="space-y-4 pb-6">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-bold">List Employee</h1>
                </div>
                <div class="flex space-x-6">
                    <Link
                        class="bg-purple-500 px-3 py-2 rounded-xl text-sm text-slate-100 cursor-pointer"
                        :href="route('admin.employees.create')"
                    >
                        Add Employee
                    </Link>
                    <a
                        target="_blank"
                        :href="route('admin.employees.export')"
                        class="bg-green-500 px-3 py-2 rounded-xl text-sm text-white hover:bg-green-600"
                    >
                        Export Excel
                    </a>
                </div>
            </div>

            <div class="card">
                <DataTable
                    :value="employees.data"
                    stripedRows
                    paginator
                    showGridlines
                    scrollable
                    :rows="10"
                    :rowsPerPageOptions="[5, 10, 20, 50]"
                    tableStyle="min-width: 50rem"
                    v-model:filters="filters"
                    dataKey="id"
                    filterDisplay="row"
                    :loading="loading"
                    :globalFilterFields="['name', 'email']"
                >
                    <template #header>
                        <div class="flex justify-start">
                            <IconField>
                                <InputIcon>
                                    <i class="pi pi-search" />
                                </InputIcon>
                                <InputText
                                    v-model="filters['global'].value"
                                    placeholder="Keyword Search"
                                />
                            </IconField>
                        </div>
                    </template>
                    <template #empty>No employees found.</template>
                    <template #loading
                        >Loading employees data. Please wait.</template
                    >

                    <Column
                        field="name"
                        header="Name"
                        sortable
                        filter
                        style="min-width: 12rem"
                    >
                        <template #body="{ data }">
                            {{ data.name }}
                        </template>
                        <template #filter="{ filterModel, filterCallback }">
                            <InputText
                                v-model="filterModel.value"
                                type="text"
                                @input="filterCallback()"
                                placeholder="Search by name"
                            />
                        </template>
                    </Column>
                    <Column field="email" header="Email" sortable filter>
                        <template #body="{ data }">
                            {{ data.email }}
                        </template>
                        <template #filter="{ filterModel, filterCallback }">
                            <InputText
                                v-model="filterModel.value"
                                type="text"
                                @input="filterCallback()"
                                placeholder="Search by email"
                            />
                        </template>
                    </Column>
                    <Column header="Action" style="min-width: 10rem">
                        <template #body="{ data }">
                            <div class="flex gap-2">
                                <Button
                                    asChild
                                    v-slot="slotProps"
                                    severity="info"
                                    size="small"
                                >
                                    <Link
                                        :href="
                                            route(
                                                'admin.employees.edit',
                                                data.id
                                            )
                                        "
                                        :class="slotProps.class"
                                        >Edit</Link
                                    >
                                </Button>

                                <Button
                                    @click="deleteEmployee(data.id)"
                                    label="Delete"
                                    severity="danger"
                                    size="small"
                                    icon="pi pi-trash"
                                />
                            </div>
                        </template>
                    </Column>
                </DataTable>
            </div>
        </div>
    </AppLayout>
</template>
