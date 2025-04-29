<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import AppLayout from "../../../Layouts/AppLayout.vue";
import {
    Button,
    Checkbox,
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
import { onMounted, ref } from "vue";
import { useFilters } from "../../../Composables/useFilter";
import { formatDateTime } from "../../../Composables/useFormatter";
import DeleteDialog from "../../../Components/DeleteDialog.vue";
import DetailPermissionIsApprove from "../../../Components/Permissions/DetailPermissionIsApprove.vue";

const page = usePage();
const loading = ref(false);
const deleteDialogVisible = ref(false);
const selectedPermission = ref(null);
const detailPermission = ref(false);
const permission_types = ref(["leave", "permission", "sick"]);

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

const getTypesBadge = (type) => {
    switch (type) {
        case "leave":
            return "danger";

        case "permission":
            return "info";

        case "sick":
            return "warn";
    }
};

const confirmDeletePermission = (employee) => {
    selectedPermission.value = employee;
    deleteDialogVisible.value = true;
};

const handleDeletePermission = () => {
    router.delete(
        route("admin.permissions.destroy", selectedPermission.value.id),
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
        <h1 class="text-2xl font-bold mb-4 underline">Attendance Permission</h1>

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
                    style="min-width: 10rem"
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
                    style="min-width: 10rem"
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
                    style="min-width: 12rem"
                >
                    <template #body="{ data }">
                        <Tag
                            :value="data.type"
                            :severity="getTypesBadge(data.type)"
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
                                    :severity="getTypesBadge(slotProps.option)"
                                />
                            </template>
                        </Select>
                    </template>
                </Column>

                <Column
                    field="is_approved"
                    header="Is Approved"
                    dataType="boolean"
                    style="min-width: 6rem"
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

                <Column header="Action" style="min-width: 10rem">
                    <template #body="{ data }">
                        <div class="flex gap-2">
                            <Button
                                label="Detail"
                                severity="info"
                                @click="
                                    selectedPermission = data;
                                    detailPermission = true;
                                "
                            />

                            <Button
                                label="Delete"
                                severity="danger"
                                size="small"
                                icon="pi pi-trash"
                                @click="confirmDeletePermission(data)"
                            />
                        </div>
                    </template>
                </Column>
            </DataTable>
        </div>

        <DetailPermissionIsApprove
            :visible="detailPermission"
            @update:visible="(val) => (detailPermission = val)"
            :selectedPermission="selectedPermission"
        />

        <DeleteDialog
            :visible="deleteDialogVisible"
            :target-name="selectedPermission?.name"
            @update:visible="deleteDialogVisible = $event"
            @confirm="handleDeletePermission"
        />
    </AppLayout>
</template>

<style scoped></style>
