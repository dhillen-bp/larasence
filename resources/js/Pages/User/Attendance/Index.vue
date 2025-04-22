<script setup>
import { useLocation } from "../../../Composables/useLocation";
import AppLayout from "../../../Layouts/AppLayout.vue";
import { onMounted, ref, watch, watchEffect } from "vue";
import { Link, router, usePage } from "@inertiajs/vue3";
import { toast } from "vue3-toastify";
import { debounce } from "lodash";
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
import { FilterMatchMode } from "@primevue/core/api";

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

const { checkIn, checkOut } = useLocation();

const page = usePage();
const date = ref(props.filters.date || "");
const status = ref(props.filters.status || "");
const loading = ref(false);
const statuses = ref(["on_time", "late", "absent", "permission"]);
const filters = ref();

const initFilters = () => {
    filters.value = {
        global: { value: null, matchMode: FilterMatchMode.CONTAINS },
        "user.name": { value: null, matchMode: FilterMatchMode.CONTAINS },
        status: { value: null, matchMode: FilterMatchMode.EQUALS },
        check_in: { value: null, matchMode: FilterMatchMode.DATE_IS },
        check_out: { value: null, matchMode: FilterMatchMode.DATE_IS },
    };
};

initFilters();

const clearFilter = () => {
    initFilters();
};

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

const getStatusBadge = (status) => {
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
                            @click="clearFilter()"
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
                            :severity="getStatusBadge(data.status)"
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
                                    :severity="getStatusBadge(slotProps.option)"
                                />
                            </template>
                        </Select>
                    </template>
                </Column>
            </DataTable>
        </div>
    </AppLayout>
</template>
