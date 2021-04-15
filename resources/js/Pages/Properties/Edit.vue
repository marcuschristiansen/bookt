<template>
    <app-layout>
        <div class="flex flex-wrap mt-4">
            <div class="w-full mb-12 xl:mb-0 px-4">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded"
                >
                    <div class="rounded-t mb-0 px-4 py-3 border-0">
                        <h1>{{ property.data.attributes.name }}</h1>
                        <div class="my-4">
                            <form @submit.prevent="update">
                                <div class="relative w-full mb-3">
                                    <jet-label for="name" value="Name" />
                                    <jet-input
                                        id="name"
                                        v-model="form.name"
                                        type="text"
                                        class="mt-1 block w-full"
                                        autocomplete="name"
                                    />
                                    <jet-input-error
                                        :message="form.errors.name"
                                        class="mt-2"
                                    />
                                </div>
                                <div class="relative w-full mb-3">
                                    <jet-label for="address" value="Address" />
                                    <jet-input
                                        id="address"
                                        v-model="form.address"
                                        type="text"
                                        class="mt-1 block w-full"
                                        autocomplete="address"
                                    />
                                    <jet-input-error
                                        :message="form.errors.address"
                                        class="mt-2"
                                    />
                                </div>
                                <div class="relative w-full mb-3">
                                    <jet-label
                                        for="contact-number"
                                        value="Contact number"
                                    />
                                    <jet-input
                                        id="contact-number"
                                        v-model="form.contact_number"
                                        type="text"
                                        class="mt-1 block w-full"
                                        autocomplete="contact-number"
                                    />
                                    <jet-input-error
                                        :message="form.errors.contact_number"
                                        class="mt-2"
                                    />
                                </div>
                                <div class="relative w-full mb-3">
                                    <jet-label
                                        for="description"
                                        value="Description"
                                    />
                                    <jet-input
                                        id="description"
                                        v-model="form.description"
                                        type="text"
                                        class="mt-1 block w-full"
                                        autocomplete="description"
                                    />
                                    <jet-input-error
                                        :message="form.errors.description"
                                        class="mt-2"
                                    />
                                </div>
                                <div class="relative w-full mb-3">
                                    <label class="flex items-center">
                                        <jet-checkbox
                                            id="is-private"
                                            v-model:checked="form.is_private"
                                            name="is-private"
                                        />
                                        <span class="ml-2 text-sm text-gray-600"
                                            >Is private</span
                                        >
                                    </label>
                                    <jet-input-error
                                        :message="form.errors.is_private"
                                        class="mt-2"
                                    />
                                </div>
                                <jet-button
                                    :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing"
                                >
                                    Update
                                </jet-button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import JetInput from '@/Jetstream/Input'
import JetCheckbox from '@/Jetstream/Checkbox'
import JetInputError from '@/Jetstream/InputError'
import JetLabel from '@/Jetstream/Label'
import JetButton from '@/Jetstream/Button'

export default {
    components: {
        AppLayout,
        JetInput,
        JetCheckbox,
        JetInputError,
        JetLabel,
        JetButton,
    },
    props: {
        property: {
            type: Object,
            default: () => {},
        },
    },
    data() {
        return {
            form: this.$inertia.form(this.property.data.attributes),
        }
    },
    methods: {
        update() {
            this.form.put(route('properties.update', this.property.data.id), {
                errorBag: 'update',
                preserveScroll: true,
            })
        },
    },
}
</script>
