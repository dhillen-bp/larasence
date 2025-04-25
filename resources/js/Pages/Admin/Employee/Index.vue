<script setup>
import { Link, router, useForm, usePage } from "@inertiajs/vue3";
import AppLayout from "../../../Layouts/AppLayout.vue";
import { showToastSuccess } from "../../../Composables/useToast";
import { ref } from "vue";
import { Button, Column, DataTable, Dialog } from "primevue";
import IconField from "primevue/iconfield";
import InputIcon from "primevue/inputicon";
import InputText from "primevue/inputtext";
import { useFilters } from "../../../Composables/useFilter";
import EditDialog from "../../../Components/Employees/FormEmployeeDialog.vue";
import DeleteDialog from "../../../Components/DeleteDialog.vue";
import FormEmployeeDialog from "../../../Components/Employees/FormEmployeeDialog.vue";

defineProps({
    employees: {
        type: Object,
        required: true,
    },
});

const deleteDialogVisible = ref(false);
const selectedEmployee = ref(null);

const { filters, initEmployeeFilters, clearFilter } = useFilters();

initEmployeeFilters();

const page = usePage();
const loading = ref(false);

const formDialogVisible = ref(false);
const formDialogMode = ref("add"); // or 'edit'
const formEmployeeData = ref(null);

const openAddDialog = () => {
    formDialogMode.value = "add";
    formEmployeeData.value = null;
    formDialogVisible.value = true;
};

const openEditDialog = (employee) => {
    formDialogMode.value = "edit";
    formEmployeeData.value = { ...employee };
    formDialogVisible.value = true;
};

const handleSaved = () => {
    formDialogVisible.value = false;

    showToastSuccess("Data saved successfully!");
};

const confirmDeleteEmployee = (employee) => {
    selectedEmployee.value = employee;
    deleteDialogVisible.value = true;
};

const handleDeleteEmployee = () => {
    router.delete(route("admin.employees.destroy", selectedEmployee.value.id), {
        onSuccess: () => {
            showToastSuccess(page.props.flash.success);
            deleteDialogVisible.value = false;
        },
    });
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
                    <Button
                        label="Add Employee"
                        icon="pi pi-plus"
                        class="bg-purple-500 text-white"
                        @click="openAddDialog"
                        size="small"
                    />
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
                        <div class="flex justify-between">
                            <IconField>
                                <InputIcon>
                                    <i class="pi pi-search" />
                                </InputIcon>
                                <InputText
                                    v-model="filters['global'].value"
                                    placeholder="Keyword Search"
                                />
                            </IconField>
                            <Button
                                type="button"
                                icon="pi pi-filter-slash"
                                label="Clear"
                                outlined
                                @click="clearFilter(initEmployeeFilters)"
                            />
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
                                    label="Edit"
                                    icon="pi pi-pencil"
                                    size="small"
                                    severity="info"
                                    @click="openEditDialog(data)"
                                />

                                <Button
                                    label="Delete"
                                    severity="danger"
                                    size="small"
                                    icon="pi pi-trash"
                                    @click="confirmDeleteEmployee(data)"
                                />
                            </div>
                        </template>
                    </Column>
                </DataTable>
            </div>

            <DeleteDialog
                :visible="deleteDialogVisible"
                :target-name="selectedEmployee?.name"
                @update:visible="deleteDialogVisible = $event"
                @confirm="handleDeleteEmployee"
            />

            <FormEmployeeDialog
                :visible="formDialogVisible"
                :mode="formDialogMode"
                :employee="formEmployeeData"
                @update:visible="formDialogVisible = $event"
                @saved="handleSaved"
            />
        </div>
    </AppLayout>
</template>
