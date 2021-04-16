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
                                    Properties for Team:
                                    {{ $page.props.user.current_team.name }}
                                </h3>
                            </div>
                            <div
                                class="relative w-full px-4 max-w-full flex-grow flex-1 text-right"
                            >
                                <jet-nav-link
                                    :href="route('properties.create')"
                                    class="text-white text-xs font-bold uppercase px-3 py-1 rounded outline-none border focus:outline-none mr-1 mb-1 inline hover:text-white"
                                    type="button"
                                    style="transition: all 0.15s ease"
                                >
                                    Create New
                                </jet-nav-link>
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
                                    <th
                                        class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                    >
                                        Visibility
                                    </th>
                                    <th
                                        class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                    ></th>
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
                                        <jet-nav-link
                                            class="inline"
                                            :href="
                                                route(
                                                    'properties.show',
                                                    property.id
                                                )
                                            "
                                        >
                                            {{ property.attributes.name }}
                                        </jet-nav-link>
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
                                    <td
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                                    >
                                        <i
                                            v-if="
                                                property.attributes.is_private
                                            "
                                            class="fas fa-lock text-gray-800 mr-4"
                                        ></i>
                                        <i
                                            v-if="
                                                !property.attributes.is_private
                                            "
                                            class="fas fa-lock-open text-gray-800 mr-4"
                                        ></i>
                                    </td>
                                    <td
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                                    >
                                        <jet-nav-link
                                            class="inline"
                                            :href="
                                                route(
                                                    'properties.edit',
                                                    property.id
                                                )
                                            "
                                        >
                                            <i
                                                class="fas fa-edit text-gray-800 mr-4"
                                            ></i>
                                        </jet-nav-link>

                                        <i
                                            class="fas fa-trash-alt text-gray-800 mr-4 cursor-pointer"
                                            @click="deleteProperty(property.id)"
                                        ></i>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import JetNavLink from '@/Jetstream/NavLink'

export default {
    components: {
        AppLayout,
        JetNavLink,
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
    methods: {
        deleteProperty(id) {
            if (!confirm('Are you sure want to remove this property?')) return
            this.$inertia.delete(route('properties.destroy', id), {})
        },
    },
}
</script>
