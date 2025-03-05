<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import AppLayout from "../../../Layouts/AppLayout.vue";
import { showToastSuccess } from "../../../Composables/useToast";

const props = defineProps({
    attendance: {
        type: Object,
        required: true,
    },
});

const page = usePage();

const form = useForm({
    status: props.attendance.data.status,
});

console.log(props.attendance.data.stat);

const updateStatus = () => {
    form.patch(route("admin.attendances.update", props.attendance.data.id), {
        onSuccess: () => showToastSuccess(page.props.flash.success),
    });
};
</script>

<template>
    <AppLayout>
        <div>
            <h2 class="mb-8 font-bold text-xl">Edit Attendance Status</h2>
            <form @submit.prevent="updateStatus">
                <div class="mb-6">
                    <label
                        for="user_name"
                        class="block text-sm font-medium text-gray-700 mb-2"
                    >
                        User Name
                    </label>
                    <input
                        type="text"
                        id="user_name"
                        :value="props.attendance.data.user.name"
                        class="md:w-1/2 rounded-lg border border-gray-300 bg-gray-100 py-2 px-3 text-sm text-gray-700 shadow-sm focus:outline-none focus:ring-0 focus:border-gray-400 cursor-not-allowed"
                        disabled
                    />
                </div>
                <div class="mb-6">
                    <label
                        for="check_in"
                        class="block text-sm font-medium text-gray-700 mb-2"
                    >
                        Check In
                    </label>
                    <input
                        type="text"
                        id="check_in"
                        :value="props.attendance.data.check_in"
                        class="md:w-1/2 rounded-lg border border-gray-300 bg-gray-100 py-2 px-3 text-sm text-gray-700 shadow-sm focus:outline-none focus:ring-0 focus:border-gray-400 cursor-not-allowed"
                        readonly
                    />
                </div>
                <div class="mb-6">
                    <label
                        for="check_out"
                        class="block text-sm font-medium text-gray-700 mb-2"
                    >
                        Check Out
                    </label>
                    <input
                        type="text"
                        id="check_out"
                        :value="props.attendance.data.check_out ?? '-'"
                        class="md:w-1/2 rounded-lg border border-gray-300 bg-gray-100 py-2 px-3 text-sm text-gray-700 shadow-sm focus:outline-none focus:ring-0 focus:border-gray-400 cursor-not-allowed"
                        readonly
                    />
                </div>
                <div class="mb-6">
                    <label
                        for="status"
                        class="block text-sm font-medium text-gray-700 mb-2"
                    >
                        Status
                    </label>
                    <select
                        v-model="form.status"
                        id="status"
                        class="md:w-1/2 rounded-lg border border-purple-200 bg-white py-2 pl-2 pr-4 text-sm text-gray-700 shadow-sm focus:outline-none focus:ring-0 focus:border-purple-500"
                    >
                        <option value="pending">Pending</option>
                        <option value="late">Late</option>
                        <option value="on_time">On Time</option>
                        <option value="absent">Absent</option>
                    </select>
                    <div
                        class="invalid-feedback-text"
                        v-if="form.errors.status"
                    >
                        {{ form.errors.status }}
                    </div>
                </div>

                <button
                    type="submit"
                    class="cursor-pointer inline-block shrink-0 rounded-md border border-purple-600 bg-purple-600 px-3 py-1.5 text-sm font-medium text-white transition hover:bg-transparent hover:text-purple-600 focus:ring-3 focus:outline-hidden"
                >
                    Update Status
                </button>
            </form>
        </div>
    </AppLayout>
</template>
