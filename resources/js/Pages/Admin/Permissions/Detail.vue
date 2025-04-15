<script setup>
import { Link, router, useForm, usePage } from "@inertiajs/vue3";
import AppLayout from "../../../Layouts/AppLayout.vue";
import { showToastSuccess } from "../../../Composables/useToast";

defineProps({
    permission: {
        type: Object,
        required: true,
    },
});

const page = usePage();
const form = useForm();

const approvePermission = (id) => {
    form.patch(route("admin.permissions.approve", id), {
        onSuccess: () => {
            if (page.props.flash.success) {
                showToastSuccess(page.props.flash.success);
            }
        },
    });
};

const rejectPermission = (id) => {
    form.patch(route("admin.permissions.reject", id), {
        onSuccess: () => {
            if (page.props.flash.success) {
                showToastSuccess(page.props.flash.success);
            }
        },
    });
};
</script>

<template>
    <AppLayout>
        <div class="p-6 bg-white rounded-lg shadow-md">
            <h1 class="text-2xl font-bold mb-8">Detail Permission Request</h1>
            <div class="mb-4 space-y-2">
                <p>
                    <strong>Employee:</strong> {{ permission.data.user.name }}
                </p>
                <p><strong>Type:</strong> {{ permission.data.type }}</p>
                <p>
                    <strong>Start Date:</strong>
                    {{ permission.data.start_date }}
                </p>
                <p>
                    <strong>End Date:</strong>
                    {{ permission.data.end_date }}
                </p>
                <p><strong>Note:</strong> {{ permission.data.note }}</p>

                <div v-if="permission.data.attachment">
                    <strong>Attachment:</strong>
                    <div class="mt-2">
                        <img
                            :src="`/storage/${permission.data.attachment}`"
                            alt="Attachment"
                            class="rounded border max-w-sm"
                        />
                    </div>
                </div>
            </div>
            <div class="flex justify-between space-x-4">
                <Link
                    :href="route('admin.permissions.index')"
                    class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-700"
                >
                    Back
                </Link>
                <button
                    v-if="permission.data.is_approved == 0"
                    @click="approvePermission(permission.data.id)"
                    class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-700 cursor-pointer"
                >
                    Approve
                </button>
                <button
                    v-if="permission.data.is_approved == 1"
                    @click="rejectPermission(permission.data.id)"
                    class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-700 cursor-pointer"
                >
                    Reject
                </button>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped></style>
