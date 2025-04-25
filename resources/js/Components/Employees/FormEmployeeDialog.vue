<script setup>
import { ref, watch } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import { Dialog, Button, InputText, Password, Message } from "primevue";

const props = defineProps({
    visible: Boolean,
    mode: { type: String, default: "add" },
    employee: Object,
});

const emit = defineEmits(["update:visible", "saved"]);

const form = useForm({
    name: "",
    email: "",
    password: "",
});

watch(
    () => props.employee,
    (val) => {
        if (props.mode === "edit" && val) {
            form.name = val.name;
            form.email = val.email;
        } else {
            form.name = "";
            form.email = "";
        }
    },
    { immediate: true }
);

const handleSubmit = () => {
    if (props.mode === "add") {
        form.post(route("admin.employees.store"), {
            onSuccess: () => emit("saved"),
        });
    } else if (props.employee?.id) {
        form.put(route("admin.employees.update", props.employee.id), {
            onSuccess: () => emit("saved"),
        });
    }
};
</script>

<template>
    <Dialog
        :visible="visible"
        @update:visible="$emit('update:visible', $event)"
        :header="mode === 'edit' ? 'Edit Employee' : 'Add Employee'"
        modal
        :style="{ width: '30rem' }"
    >
        <form @submit.prevent="handleSubmit" class="space-y-4">
            <div>
                <label class="block mb-1 text-sm font-medium">Name</label>
                <InputText
                    v-model="form.name"
                    type="text"
                    class="w-full input"
                />
                <small v-if="form.errors.name" class="p-error block mt-1">
                    {{ form.errors.name }}
                </small>
            </div>

            <div>
                <label class="block mb-1 text-sm font-medium">Email</label>
                <InputText
                    v-model="form.email"
                    type="email"
                    class="w-full input"
                />
                <Message
                    v-if="form.errors.email"
                    severity="error"
                    size="small"
                    variant="simple"
                >
                    {{ form.errors.email }}
                </Message>
            </div>

            <div>
                <label class="block mb-1 text-sm font-medium"
                    >Password
                    <span
                        v-if="mode === 'edit'"
                        severity="error"
                        size="small"
                        variant="simple"
                        >(leave blank to keep current)</span
                    ></label
                >
                <Password
                    v-model="form.password"
                    type="password"
                    class="w-full input"
                    :inputStyle="{ width: '100%' }"
                />
                <Message
                    v-if="form.errors.password"
                    severity="error"
                    size="small"
                    variant="simple"
                >
                    {{ form.errors.password }}
                </Message>
            </div>
        </form>

        <template #footer>
            <Button
                label="Cancel"
                severity="secondary"
                @click="$emit('update:visible', false)"
                type="button"
            />
            <Button
                :label="mode === 'edit' ? 'Update' : 'Save'"
                severity="primary"
                @click="handleSubmit"
            />
        </template>
    </Dialog>
</template>
