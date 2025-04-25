<script setup>
import { Button, Dialog } from "primevue";

defineProps({
    visible: Boolean,
});

defineEmits(["update:visible", "confirm"]);
</script>

<template>
    <Dialog
        :visible="visible"
        @update:visible="$emit('update:visible', $event)"
        :style="{ width: '25rem' }"
        header="Confirm Delete"
        modal
    >
        <div class="text-center">
            <p class="mb-4">Are you sure you want to delete?</p>
        </div>

        <template #footer>
            <Button
                label="Cancel"
                severity="secondary"
                @click="$emit('update:visible', false)"
            />
            <Button
                label="Delete"
                severity="danger"
                @click="$emit('confirm')"
            />
        </template>
    </Dialog>
</template>
