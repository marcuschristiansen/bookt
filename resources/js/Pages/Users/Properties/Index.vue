<template>
    <app-layout>
        <div class="flex flex-wrap mt-4">
            <div class="w-full mb-12 xl:mb-0 px-4">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded"
                >
                    <div class="rounded-t mb-0 px-4 py-3 border-0">
                        <div class="flex flex-wrap items-center">
                            <div
                                class="relative w-full px-4 max-w-full flex-grow flex-1"
                            >
                                <h3
                                    class="font-semibold text-base text-blueGray-700"
                                >
                                    Your memberships
                                </h3>
                            </div>
                            <div
                                class="relative w-full px-4 max-w-full flex-grow flex-1 text-right"
                            ></div>
                            <div
                                class="relative w-full px-4 max-w-full flex-grow flex-1 text-right"
                            >
                                <button
                                    class="text-gray-500 hover:text-gray-800 text-xs uppercase py-3 font-bold block text-white text-xs font-bold uppercase px-3 py-1 rounded outline-none border focus:outline-none mr-1 mb-1 inline hover:text-white"
                                    @click="isJoinModalOpen = true"
                                >
                                    Join
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="block w-full overflow-x-auto">
                        <!-- Projects table -->
                        <table
                            class="items-center w-full bg-transparent border-collapse"
                        >
                            <thead>
                                <tr>
                                    <th
                                        class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                    >
                                        Name
                                    </th>
                                    <th
                                        class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                    >
                                        Address
                                    </th>
                                    <th
                                        class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                    >
                                        Contact Number
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="property in properties.data"
                                    :key="property.id"
                                >
                                    <th
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-left"
                                    >
                                        {{ property.attributes.name }}
                                    </th>
                                    <td
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                                    >
                                        {{ property.attributes.address }}
                                    </td>
                                    <td
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                                    >
                                        {{ property.attributes.contact_number }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <jet-dialog-modal :show="isJoinModalOpen" @close="closeModal">
            <template #title>Join</template>
            <template #content>
                Please enter the join code
                <form @submit.prevent="joinProperty">
                    <jet-input
                        id="name"
                        v-model="form.joining_code"
                        type="text"
                        class="mt-1 block w-full"
                        autocomplete="joining-code"
                    />
                    <jet-input-error
                        :message="form.errors.joining_code"
                        class="mt-2"
                    />
                    <jet-input-error
                        :message="form.errors.property_id"
                        class="mt-2"
                    />
                </form>
            </template>
            <template #footer>
                <jet-secondary-button @click.native="isJoinModalOpen = false">
                    Nevermind
                </jet-secondary-button>

                <jet-button
                    class="ml-2"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click.native="joinProperty"
                >
                    Join
                </jet-button>
            </template>
        </jet-dialog-modal>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import JetNavLink from '@/Jetstream/NavLink'
import JetDialogModal from '@/Jetstream/DialogModal'
import JetButton from '@/Jetstream/Button'
import JetSecondaryButton from '@/Jetstream/SecondaryButton'
import Button from '@/Jetstream/Button'
import JetInput from '@/Jetstream/Input'
import JetInputError from '@/Jetstream/InputError'

export default {
    components: {
        Button,
        AppLayout,
        JetNavLink,
        JetDialogModal,
        JetButton,
        JetSecondaryButton,
        JetInput,
        JetInputError,
    },
    props: {
        properties: {
            type: Object,
            default: () => {},
        },
        user: {
            type: Object,
            default: () => {},
        },
    },
    data() {
        return {
            form: this.$inertia.form({
                joining_code: '',
            }),
            isJoinModalOpen: false,
        }
    },
    methods: {
        deleteProperty(id) {
            if (!confirm('Are you sure want to remove this property?')) return
            this.$inertia.delete(route('properties.destroy', id), {})
        },
        joinProperty() {
            this.form.post(route('user-properties.store', this.user.id), {
                errorBag: 'save',
                preserveScroll: true,
                onSuccess: () => this.closeModal(),
            })
        },
        closeModal() {
            this.isJoinModalOpen = false
            this.form.joining_code = ''
        },
    },
}
</script>
