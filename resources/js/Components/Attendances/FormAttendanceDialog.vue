<script setup>
import { ref, watch } from "vue";
import { useForm } from "@inertiajs/vue3";
import { Dialog, Button, InputText, Select } from "primevue";
import { formatDateTime } from "../../Composables/useFormatter";

const props = defineProps({
    visible: Boolean,
    attendance: Object,
});

const emit = defineEmits(["update:visible", "saved"]);

const form = useForm({
    status: "",
});

const statusOptions = [
    { label: "Pending", value: "pending" },
    { label: "On Time", value: "on_time" },
    { label: "Absent", value: "absent" },
    { label: "Permission", value: "permission" },
    { label: "Late", value: "late" },
];

watch(
    () => props.attendance,
    (newVal) => {
        if (newVal) {
            form.status = newVal.status || "";
        }
    },
    { immediate: true }
);

const handleSubmit = () => {
    if (!props.attendance?.id) return;

    form.put(route("admin.attendances.update", props.attendance.id), {
        onSuccess: () => {
            emit("saved");
        },
    });
};
</script>

<template>
    <Dialog
        :visible="visible"
        @update:visible="$emit('update:visible', $event)"
        header="Edit Attendance"
        modal
        :style="{ width: '30rem' }"
    >
        <form @submit.prevent="handleSubmit" class="space-y-4">
            <div>
                <label class="block mb-1 text-sm font-medium"
                    >Employee Name</label
                >
                <InputText
                    :value="props.attendance?.user?.name"
                    type="text"
                    class="w-full input"
                    readonly
                />
            </div>

            <div>
                <label class="block mb-1 text-sm font-medium">Check In</label>
                <InputText
                    :value="
                        props.attendance?.check_in
                            ? formatDateTime(props.attendance.check_in)
                            : ''
                    "
                    type="text"
                    class="w-full input"
                    readonly
                />
            </div>

            <div>
                <label class="block mb-1 text-sm font-medium">Check Out</label>
                <InputText
                    :value="
                        props.attendance?.check_out
                            ? formatDateTime(props.attendance.check_out)
                            : ''
                    "
                    type="text"
                    class="w-full input"
                    readonly
                />
            </div>

            <div>
                <label class="block mb-1 text-sm font-medium">Status</label>
                <Select
                    v-model="form.status"
                    :options="statusOptions"
                    optionLabel="label"
                    optionValue="value"
                    placeholder="Select Status"
                    class="w-full"
                />
            </div>
        </form>

        <template #footer>
            <Button
                label="Cancel"
                severity="secondary"
                @click="$emit('update:visible', false)"
                type="button"
            />
            <Button :label="'Save'" severity="primary" @click="handleSubmit" />
        </template>
    </Dialog>
</template>
