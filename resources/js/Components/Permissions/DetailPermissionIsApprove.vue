<script setup>
import { Button, Dialog, InputText, Textarea } from "primevue";
import { router, usePage } from "@inertiajs/vue3";
import { showToastSuccess } from "../../Composables/useToast";

const props = defineProps({
    visible: Boolean,
    selectedPermission: Object,
});

const emit = defineEmits(["update:visible"]);

const page = usePage();

const approvePermission = (id) => {
    router.patch(
        route("admin.permissions.approve", id),
        {},
        {
            onSuccess: () => {
                showToastSuccess(page.props.flash.success);
                emit("update:visible", false);
            },
        }
    );
};

const rejectPermission = (id) => {
    router.patch(
        route("admin.permissions.reject", id),
        {},
        {
            onSuccess: () => {
                showToastSuccess(page.props.flash.success);
                emit("update:visible", false);
            },
        }
    );
};
</script>

<template>
    <Dialog
        :visible="visible"
        @update:visible="(val) => emit('update:visible', val)"
        modal
        header="Detail Permission"
        :style="{ width: '50vw' }"
        :breakpoints="{ '1199px': '75vw', '575px': '90vw' }"
    >
        <div class="flex flex-col gap-5">
            <div>
                <label class="block font-bold mb-3">Name</label>
                <InputText
                    :value="selectedPermission?.user?.name"
                    readonly
                    fluid
                    class="cursor-not-allowed"
                />
            </div>

            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-6">
                    <label class="block font-bold mb-3">Permission Type</label>
                    <InputText
                        :value="selectedPermission?.type"
                        readonly
                        fluid
                        class="cursor-not-allowed"
                    />
                </div>
                <div class="col-span-6">
                    <label class="block font-bold mb-3">Is Approved</label>
                    <InputText
                        :value="
                            selectedPermission?.is_approved ? 'Yes ✅' : 'No ❌'
                        "
                        readonly
                        fluid
                        class="cursor-not-allowed"
                    />
                </div>
            </div>

            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-6">
                    <label class="block font-bold mb-3">Start Date</label>
                    <InputText
                        :value="
                            selectedPermission?.start_date
                                ? new Date(
                                      selectedPermission.start_date
                                  ).toLocaleDateString('id-ID')
                                : ''
                        "
                        readonly
                        fluid
                        class="cursor-not-allowed"
                    />
                </div>
                <div class="col-span-6">
                    <label class="block font-bold mb-3">End Date</label>
                    <InputText
                        :value="
                            selectedPermission?.end_date
                                ? new Date(
                                      selectedPermission.end_date
                                  ).toLocaleDateString('id-ID')
                                : ''
                        "
                        readonly
                        fluid
                        class="cursor-not-allowed"
                    />
                </div>
            </div>

            <div>
                <label class="block font-bold mb-3">Note</label>
                <Textarea
                    :value="selectedPermission?.note"
                    readonly
                    rows="3"
                    cols="20"
                    fluid
                    class="cursor-not-allowed"
                />
            </div>

            <div>
                <label class="block font-bold mb-3">Attachment</label>
                <img
                    v-if="selectedPermission?.attachment"
                    :src="`/storage/${selectedPermission.attachment}`"
                    alt="Attachment"
                    class="block m-auto pb-4 max-w-xs"
                />
                <p v-else class="text-center text-gray-500 italic">
                    No attachment
                </p>
            </div>

            <div class="flex mt-5">
                <Button
                    label="Accept"
                    icon="pi pi-check"
                    severity="success"
                    v-if="selectedPermission?.is_approved == 0"
                    fluid
                    @click="approvePermission(selectedPermission.id)"
                />
                <Button
                    label="Reject"
                    icon="pi pi-times"
                    severity="danger"
                    v-if="selectedPermission?.is_approved == 1"
                    fluid
                    @click="rejectPermission(selectedPermission.id)"
                />
            </div>
        </div>
    </Dialog>
</template>
