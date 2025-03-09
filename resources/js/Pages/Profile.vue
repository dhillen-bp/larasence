<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import AppLayout from "../Layouts/AppLayout.vue";
import { showToastSuccess } from "../Composables/useToast";

const props = defineProps({
    profile: {
        type: Object,
        required: true,
    },
});

const page = usePage();

const form = useForm({
    name: props.profile.name,
    email: props.profile.email,
    password: null,
    image: null,
});

function submit() {
    console.log(form.image);

    form.post(route("profile.update"), {
        forceFormData: true,
        onSuccess: () => showToastSuccess(page.props.flash.success),
    });
}
</script>

<template>
    <AppLayout>
        <div>
            <h2 class="mb-8 font-bold text-xl">Edit Profile</h2>
            <form @submit.prevent="submit" enctype="multipart/form-data">
                <div class="mb-6">
                    <label
                        for="image"
                        class="block text-sm font-medium text-gray-700 mb-2"
                        >Image</label
                    >
                    <input
                        type="file"
                        @input="form.image = $event.target.files[0]"
                        class="md:w-1/2 rounded-lg border border-purple-200 bg-white py-2 pl-2 pr-4 text-sm text-gray-700 shadow-sm focus:outline-none focus:ring-0 focus:border-purple-500"
                        :class="{
                            'invalid-feedback-input': form.errors.image,
                        }"
                    />

                    <div
                        class="invalid-feedback-text"
                        v-if="form.errors.image"
                        v-text="form.errors.image"
                    ></div>
                </div>
                <div class="mb-6">
                    <label
                        for="name"
                        class="block text-sm font-medium text-gray-700 mb-2"
                        >Name</label
                    >
                    <input
                        type="text"
                        v-model="form.name"
                        id="name"
                        class="md:w-1/2 rounded-lg border border-purple-200 bg-white py-2 pl-2 pr-4 text-sm text-gray-700 shadow-sm focus:outline-none focus:ring-0 focus:border-purple-500"
                        :class="{
                            'invalid-feedback-input': form.errors.name,
                        }"
                    />
                    <div
                        class="invalid-feedback-text"
                        v-if="form.errors.name"
                        v-text="form.errors.name"
                    ></div>
                </div>
                <div class="mb-6">
                    <label
                        for="email"
                        class="block text-sm font-medium text-gray-700 mb-2"
                        >Email</label
                    >
                    <input
                        type="text"
                        v-model="form.email"
                        id="email"
                        class="md:w-1/2 rounded-lg border border-purple-200 bg-white py-2 pl-2 pr-4 text-sm text-gray-700 shadow-sm focus:outline-none focus:ring-0 focus:border-purple-500"
                        :class="{
                            'invalid-feedback-input': form.errors.email,
                        }"
                    />
                    <div
                        class="invalid-feedback-text"
                        v-if="form.errors.email"
                        v-text="form.errors.email"
                    ></div>
                </div>
                <div class="mb-6">
                    <label
                        for="password"
                        class="block text-sm font-medium text-gray-700 mb-2"
                        >Password</label
                    >
                    <input
                        type="password"
                        v-model="form.password"
                        id="password"
                        class="md:w-1/2 rounded-lg border border-purple-200 bg-white py-2 pl-2 pr-4 text-sm text-gray-700 shadow-sm focus:outline-none focus:ring-0 focus:border-purple-500"
                        :class="{
                            'invalid-feedback-input': form.errors.password,
                        }"
                    />
                    <div
                        class="invalid-feedback-text"
                        v-if="form.errors.password"
                        v-text="form.errors.password"
                    ></div>
                </div>
                <button
                    type="submit"
                    class="cursor-pointer inline-block shrink-0 rounded-md border border-purple-600 bg-purple-600 px-3 py-1.5 text-sm font-medium text-white transition hover:bg-transparent hover:text-purple-600 focus:ring-3 focus:outline-hidden"
                >
                    Submit
                </button>
            </form>
        </div>
    </AppLayout>
</template>
