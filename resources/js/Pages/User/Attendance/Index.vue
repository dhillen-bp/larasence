<script setup>
import { useLocation } from "../../../Composables/useLocation";
import AppLayout from "../../../Layouts/AppLayout.vue";
import { onMounted, ref } from "vue";
import { Link, router, usePage } from "@inertiajs/vue3";
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
import { formatDateTime } from "../../../Composables/useFormatter";
import { useBadge } from "../../../Composables/useBadge";

const props = defineProps({
    attendances: {
        type: Object,
        required: true,
    },
});

const { checkIn, checkOut } = useLocation();
const loading = ref(false);

const { filters, initAttendanceFilters, clearFilter } = useFilters();

initAttendanceFilters();

onMounted(() => {
    props.attendances.data.forEach((data) => {
        data.check_in = data.check_in ? new Date(data.check_in) : null;
        data.check_out = data.check_out ? new Date(data.check_out) : null;
    });
});

const { attendanceStatus, getBadgeClass } = useBadge();
</script>

<template>
    <AppLayout>
        <div>
            <h1 class="text-2xl font-bold mb-4 underline">Absence Action</h1>
            <div class="flex justify-between mb-4">
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
            <Link
                :href="route('permission.create')"
                class="bg-blue-500 text-white px-4 py-2 rounded cursor-pointer"
            >
                Ask Permission
            </Link>
        </div>

        <hr class="my-6 border-purple-500" />

        <div class="overflow-x-auto">
            <h1 class="text-2xl font-bold mb-4 underline">Absence History</h1>

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
                            placeholder="Search by name"
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
                            :severity="getBadgeClass(data.status, 'attendance')"
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
            </DataTable>
        </div>
    </AppLayout>
</template>
