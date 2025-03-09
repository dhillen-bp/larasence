<script setup>
import { useForm } from "@inertiajs/vue3";

const form = useForm({
    name: "John Doe",
    avatar: null,
});

function submit() {
    form.post("/test", {
        // forceFormData: true,
    });
}
</script>

<template>
    <form @submit.prevent="submit">
        <input type="text" v-model="form.name" placeholder="Name..." />
        <input type="file" @input="form.avatar = $event.target.files[0]" />
        <progress
            v-if="form.progress"
            :value="form.progress.percentage"
            max="100"
        >
            {{ form.progress.percentage }}%
        </progress>
        <button type="submit">Submit</button>
    </form>
</template>
