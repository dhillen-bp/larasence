<script setup>
import { Link, router, usePage } from "@inertiajs/vue3";
import AppLayout from "../../../Layouts/AppLayout.vue";
import { showToastSuccess } from "../../../Composables/useToast";
import { onMounted, ref } from "vue";
import {
    Button,
    Column,
    DataTable,
    DatePicker,
    Dialog,
    IconField,
    InputIcon,
    InputText,
    Select,
    Tag,
} from "primevue";
import { useFilters } from "../../../Composables/useFilter";
import { useBadge } from "../../../Composables/useBadge";
import { formatDateTime } from "../../../Composables/useFormatter";

import DeleteDialog from "../../../Components/DeleteDialog.vue";
import FormAttendanceDialog from "../../../Components/Attendances/FormAttendanceDialog.vue";

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

const page = usePage();
const loading = ref(false);
const deleteDialogVisible = ref(false);
const selectedAttendance = ref(null);
const formDialogVisible = ref(false);
const formAttendanceData = ref(null);

const { filters, initAttendanceFilters, clearFilter } = useFilters();

initAttendanceFilters();

onMounted(() => {
    props.attendances.data.forEach((data) => {
        data.check_in = data.check_in ? new Date(data.check_in) : null;
        data.check_out = data.check_out ? new Date(data.check_out) : null;
    });
});

const { attendanceStatus, getBadgeClass } = useBadge();

const openEditAttendanceDialog = (attendance) => {
    formAttendanceData.value = attendance;
    formDialogVisible.value = true;
};

const handleSaved = () => {
    formDialogVisible.value = false;

    showToastSuccess("Data attendance saved successfully!");
};

const confirmDeleteAttendance = (employee) => {
    selectedAttendance.value = employee;
    deleteDialogVisible.value = true;
};

const handleDeleteAttendance = () => {
    router.delete(
        route("admin.attendances.destroy", selectedAttendance.value.id),
        {
            onSuccess: () => {
                showToastSuccess(page.props.flash.success);
                deleteDialogVisible.value = false;
            },
        }
    );
};
</script>

<template>
    <AppLayout>
        <div class="overflow-x-auto">
            <div class="mb-6">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-bold">List Attendance</h1>
                </div>
            </div>
            <div class="mb-6">
                <a
                    target="_blank"
                    :href="route('admin.attendances.export')"
                    class="bg-green-500 px-3 py-2 rounded-xl text-sm text-white hover:bg-green-600"
                >
                    Export Excel
                </a>
            </div>

            <hr class="mt-1 border-purple-500" />

            <div class="card">
                <DataTable
                    :value="attendances.data"
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
                    :globalFilterFields="['user.name', 'status']"
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
                                @click="clearFilter(initAttendanceFilters)"
                            />
                        </div>
                    </template>
                    <template #empty>No attendances found.</template>
                    <template #loading>
                        Loading attendances data. Please wait.
                    </template>

                    <Column
                        field="user.name"
                        header="Name"
                        sortable
                        filter
                        style="min-width: 12rem"
                    >
                        <template #body="{ data }">
                            {{ data.user.name }}
                        </template>
                        <template #filter="{ filterModel, filterCallback }">
                            <InputText
                                v-model="filterModel.value"
                                type="text"
                                @input="filterCallback()"
                                placeholder="Search by employee name"
                            />
                        </template>
                    </Column>

                    <Column
                        filterField="check_in"
                        header="Check In"
                        dataType="date"
                        style="min-width: 10rem"
                    >
                        <template #body="{ data }">
                            {{ formatDateTime(data.check_in) }}
                        </template>
                        <template #filter="{ filterModel }">
                            <DatePicker
                                v-model="filterModel.value"
                                dateFormat="dd/mm/yy"
                                placeholder="Select Date"
                            />
                        </template>
                    </Column>

                    <Column
                        filterField="check_out"
                        header="Check Out"
                        dataType="date"
                        style="min-width: 10rem"
                    >
                        <template #body="{ data }">
                            {{ formatDateTime(data.check_out) }}
                        </template>
                        <template #filter="{ filterModel }">
                            <DatePicker
                                v-model="filterModel.value"
                                dateFormat="dd/mm/yy"
                                placeholder="Select Date"
                            />
                        </template>
                    </Column>

                    <Column
                        field="status"
                        header="Status"
                        :showFilterMenu="false"
                        style="min-width: 12rem"
                    >
                        <template #body="{ data }">
                            <Tag
                                :value="data.status"
                                :severity="
                                    getBadgeClass(data.status, 'attendance')
                                "
                            />
                        </template>
                        <template #filter="{ filterModel, filterCallback }">
                            <Select
                                v-model="filterModel.value"
                                @change="filterCallback()"
                                :options="attendanceStatus"
                                placeholder="Select One"
                                style="min-width: 12rem"
                                :showClear="true"
                            >
                                <template #option="slotProps">
                                    <Tag
                                        :value="slotProps.option"
                                        :severity="
                                            getBadgeClass(
                                                slotProps.option,
                                                'attendance'
                                            )
                                        "
                                    />
                                </template>
                            </Select>
                        </template>
                    </Column>

                    <Column header="Action" style="min-width: 10rem">
                        <template #body="{ data }">
                            <div class="flex gap-2">
                                <Button
                                    label="Edit"
                                    size="small"
                                    icon="pi pi-pencil"
                                    severity="info"
                                    @click="openEditAttendanceDialog(data)"
                                />

                                <Button
                                    label="Delete"
                                    severity="danger"
                                    size="small"
                                    icon="pi pi-trash"
                                    @click="confirmDeleteAttendance(data)"
                                />
                            </div>
                        </template>
                    </Column>
                </DataTable>
            </div>

            <FormAttendanceDialog
                :visible="formDialogVisible"
                :attendance="formAttendanceData"
                @update:visible="formDialogVisible = $event"
                @saved="handleSaved"
            />

            <DeleteDialog
                :visible="deleteDialogVisible"
                :target-name="selectedAttendance?.name"
                @update:visible="deleteDialogVisible = $event"
                @confirm="handleDeleteAttendance"
            />
        </div>
    </AppLayout>
</template>

<style scoped></style>
