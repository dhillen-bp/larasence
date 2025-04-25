<script setup>
import { Dialog, InputText, Textarea } from "primevue";

const props = defineProps({
    visible: Boolean,
    selectedPermission: Object,
});

const emit = defineEmits(["update:visible"]);
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
                />
            </div>

            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-6">
                    <label class="block font-bold mb-3">Permission Type</label>
                    <InputText
                        :value="selectedPermission?.type"
                        readonly
                        fluid
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
                    />
                </div>
            </div>

            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-6">
                    <label class="block font-bold mb-3">Start Date</label>
                    <InputText
                        :value="
                            selectedPermission?.start_date?.toLocaleDateString(
                                'id-ID'
                            )
                        "
                        readonly
                        fluid
                    />
                </div>
                <div class="col-span-6">
                    <label class="block font-bold mb-3">End Date</label>
                    <InputText
                        :value="
                            selectedPermission?.end_date?.toLocaleDateString(
                                'id-ID'
                            )
                        "
                        readonly
                        fluid
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
        </div>
    </Dialog>
</template>
