<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import AppLayout from "../../../../Layouts/AppLayout.vue";
import {
    Button,
    Checkbox,
    Column,
    DataTable,
    DatePicker,
    IconField,
    InputIcon,
    InputText,
    Select,
    Tag,
} from "primevue";
import { onMounted, ref } from "vue";
import { useFilters } from "../../../../Composables/useFilter";
import { formatDateTime } from "../../../../Composables/useFormatter";
import { useBadge } from "../../../../Composables/useBadge";

const loading = ref(false);

const props = defineProps({
    permissions: {
        type: Object,
        required: true,
    },
});

const { filters, initPermissionFilters, clearFilter } = useFilters();

initPermissionFilters();

onMounted(() => {
    props.permissions.data.forEach((data) => {
        data.start_date = data.start_date ? new Date(data.start_date) : null;
        data.end_date = data.end_date ? new Date(data.end_date) : null;
        data.is_approved = Boolean(data.is_approved);
    });
});

const { permission_types, getBadgeClass } = useBadge();
</script>

<template>
    <AppLayout>
        <div class="mb-6 space-y-3">
            <h1 class="text-2xl font-bold mb-4 underline">
                Attendance Permission
            </h1>

            <Link
                :href="route('permission.create')"
                class="bg-blue-500 text-white px-4 py-2 rounded cursor-pointer"
            >
                Ask Permission
            </Link>
        </div>

        <div class="card overflow-auto max-w-[1050px]">
            <DataTable
                :value="permissions.data"
                stripedRows
                paginator
                showGridlines
                scrollable
                :rows="10"
                :rowsPerPageOptions="[5, 10, 20, 50]"
                tableStyle="max-width: 50rem"
                v-model:filters="filters"
                dataKey="id"
                filterDisplay="row"
                :loading="loading"
                :globalFilterFields="['user.name', 'type']"
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
                            @click="clearFilter(initPermissionFilters)"
                        />
                    </div>
                </template>
                <template #empty>No permissions found.</template>
                <template #loading>
                    Loading permissions data. Please wait.
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
                    filterField="start_date"
                    header="Start Date"
                    dataType="date"
                    style="min-width: 11rem"
                >
                    <template #body="{ data }">
                        {{ formatDateTime(data.start_date) }}
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
                    filterField="end_date"
                    header="End Date"
                    dataType="date"
                    style="min-width: 11rem"
                >
                    <template #body="{ data }">
                        {{ formatDateTime(data.end_date) }}
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
                    field="type"
                    header="Type"
                    :showFilterMenu="false"
                    style="min-width: 10rem"
                >
                    <template #body="{ data }">
                        <Tag
                            :value="data.type"
                            :severity="getBadgeClass(data.type, 'permission')"
                        />
                    </template>
                    <template #filter="{ filterModel, filterCallback }">
                        <Select
                            v-model="filterModel.value"
                            @change="filterCallback()"
                            :options="permission_types"
                            placeholder="Select One"
                            style="min-width: 6rem"
                            :showClear="true"
                        >
                            <template #option="slotProps">
                                <Tag
                                    :value="slotProps.option"
                                    :severity="
                                        getBadgeClass(
                                            slotProps.option,
                                            'permission'
                                        )
                                    "
                                />
                            </template>
                        </Select>
                    </template>
                </Column>

                <Column
                    field="is_approved"
                    header="Is Approved"
                    dataType="boolean"
                    style="min-width: 6rem; text-align: center"
                >
                    <template #body="{ data }">
                        <i
                            class="pi"
                            :class="{
                                'pi-check-circle text-green-500':
                                    data.is_approved,
                                'pi-times-circle text-red-400':
                                    !data.is_approved,
                            }"
                        ></i>
                    </template>
                    <template #filter="{ filterModel, filterCallback }">
                        <Checkbox
                            v-model="filterModel.value"
                            :indeterminate="filterModel.value === null"
                            binary
                            @change="filterCallback()"
                        />
                    </template>
                </Column>

                <Column header="Action" style="min-width: 8rem">
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
                                        route('admin.permissions.show', data.id)
                                    "
                                    :class="slotProps.class"
                                    >Detail</Link
                                >
                            </Button>
                        </div>
                    </template>
                </Column>
            </DataTable>
        </div>
    </AppLayout>
</template>

<style scoped></style>
