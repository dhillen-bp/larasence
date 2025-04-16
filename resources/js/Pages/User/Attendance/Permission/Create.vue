<script setup>
import AppLayout from "../../../../Layouts/AppLayout.vue";
import { ref } from "vue";
import { useForm, usePage } from "@inertiajs/vue3";
import { showToastSuccess } from "../../../../Composables/useToast";

const page = usePage();

const form = useForm({
    type: "permission", // default value
    note: "",
    start_date: "",
    end_date: "",
    attachment: null,
});

const submit = () => {
    form.post(
        route("permission.store"),
        {
            onSuccess: () => {
                showToastSuccess(page.props.flash.success);
            },
        },
        {
            preserveScroll: true,
        }
    );
};
</script>

<template>
    <AppLayout>
        <h1 class="text-xl font-bold mb-4">Form Permission Request</h1>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <label class="block mb-1 font-medium">Type</label>
                <select
                    v-model="form.type"
                    class="w-full border border-gray-300 rounded px-3 py-2"
                >
                    <option value="leave">Leave</option>
                    <option value="permission">Permission</option>
                    <option value="sick">Sick</option>
                </select>
                <div v-if="form.errors.type" class="text-red-600 text-sm">
                    {{ form.errors.type }}
                </div>
            </div>

            <div>
                <label class="block mb-1 font-medium">Note</label>
                <textarea
                    v-model="form.note"
                    class="w-full border border-gray-300 rounded px-3 py-2"
                ></textarea>
                <div v-if="form.errors.note" class="text-red-600 text-sm">
                    {{ form.errors.note }}
                </div>
            </div>

            <div class="flex gap-4">
                <div class="flex-1">
                    <label class="block mb-1 font-medium">Start Date</label>
                    <input
                        type="date"
                        v-model="form.start_date"
                        class="w-full border border-gray-300 rounded px-3 py-2"
                    />
                    <div
                        v-if="form.errors.start_date"
                        class="text-red-600 text-sm"
                    >
                        {{ form.errors.start_date }}
                    </div>
                </div>
                <div class="flex-1">
                    <label class="block mb-1 font-medium">End Date</label>
                    <input
                        type="date"
                        v-model="form.end_date"
                        class="w-full border border-gray-300 rounded px-3 py-2"
                    />
                    <div
                        v-if="form.errors.end_date"
                        class="text-red-600 text-sm"
                    >
                        {{ form.errors.end_date }}
                    </div>
                </div>
            </div>

            <div>
                <label class="block mb-1 font-medium">Attachment</label>
                <input
                    type="file"
                    @change="(e) => (form.attachment = e.target.files[0])"
                    class="w-full border border-gray-300 rounded px-3 py-2"
                />
                <div v-if="form.errors.attachment" class="text-red-600 text-sm">
                    {{ form.errors.attachment }}
                </div>
            </div>

            <button
                type="submit"
                :disabled="form.processing"
                class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700 cursor-pointer"
            >
                Submit Request
            </button>
        </form>
    </AppLayout>
</template>
