<script setup>
import { Link, router, usePage } from "@inertiajs/vue3";
import AppLayout from "../../../Layouts/AppLayout.vue";
import { showToastSuccess } from "../../../Composables/useToast";
import { onMounted, ref } from "vue";
import {
    Column,
    DataTable,
    DatePicker,
    IconField,
    InputIcon,
    InputText,
    Select,
    Tag,
} from "primevue";
import { FilterMatchMode, FilterOperator } from "@primevue/core/api";

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

const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
    "user.name": { value: null, matchMode: FilterMatchMode.CONTAINS },
    status: { value: null, matchMode: FilterMatchMode.EQUALS },
    check_in: { value: null, matchMode: FilterMatchMode.DATE_IS },
    check_out: { value: null, matchMode: FilterMatchMode.DATE_IS },
});

const statuses = ref(["on_time", "late", "absent", "permission"]);

const page = usePage();
const loading = ref(false);

const deleteEmployee = (id) => {
    if (confirm("Are you sure you want to delete this attendance?")) {
        router.delete(route("admin.attendances.destroy", id), {
            onSuccess: () => {
                showToastSuccess(page.props.flash.success);
            },
        });
    }
};

// const formatDate = (value) => {
//     return value.toLocaleDateString("en-US", {
//         day: "2-digit",
//         month: "2-digit",
//         year: "numeric",
//     });
// };

onMounted(() => {
    props.attendances.data.forEach((data) => {
        data.check_in = data.check_in ? new Date(data.check_in) : null;
        data.check_out = data.check_out ? new Date(data.check_out) : null;
    });
});

const formatDateTime = (date) => {
    if (!date) return "-";
    return new Date(date).toLocaleString("id-ID", {
        day: "2-digit",
        month: "2-digit",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit",
    });
};

const getSeverity = (status) => {
    switch (status) {
        case "absent":
            return "danger";

        case "permission":
            return "info";

        case "late":
            return "warn";

        case "on_time":
            return "success";
    }
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
                    :globalFilterFields="['user.name']"
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
                                :severity="getSeverity(data.status)"
                            />
                        </template>
                        <template #filter="{ filterModel, filterCallback }">
                            <Select
                                v-model="filterModel.value"
                                @change="filterCallback()"
                                :options="statuses"
                                placeholder="Select One"
                                style="min-width: 12rem"
                                :showClear="true"
                            >
                                <template #option="slotProps">
                                    <Tag
                                        :value="slotProps.option"
                                        :severity="
                                            getSeverity(slotProps.option)
                                        "
                                    />
                                </template>
                            </Select>
                        </template>
                    </Column>

                    <Column header="Action" style="min-width: 10rem">
                        <template #body="{ data }">
                            <div class="flex gap-2">
                                <Link
                                    :href="
                                        route('admin.attendances.edit', data.id)
                                    "
                                    class="px-3 py-1 text-sm bg-amber-500 text-slate-100 rounded-full"
                                >
                                    Edit
                                </Link>
                                <button
                                    @click="deleteEmployee(data.id)"
                                    class="px-3 py-1 text-sm bg-red-500 text-slate-100 rounded-full"
                                >
                                    Delete
                                </button>
                            </div>
                        </template>
                    </Column>
                </DataTable>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped></style>
