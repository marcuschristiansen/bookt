<template>
    <div>
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
                                Calendars
                            </h3>
                        </div>
                        <div
                            class="relative w-full px-4 max-w-full flex-grow flex-1 text-right"
                        >
                            <jet-nav-link
                                :href="
                                    route('calendars.create', property.data.id)
                                "
                                class="bg-indigo-500 text-white active:bg-indigo-600 hover:bg-indigo-400 text-xs font-bold uppercase px-3 py-1 rounded outline-none focus:outline-none mr-1 mb-1 inline hover:text-white"
                                type="button"
                                style="transition: all 0.15s ease"
                            >
                                Create New Calendar
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
                                ></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="calendar in calendars"
                                :key="calendar.id"
                            >
                                <th
                                    class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-left"
                                >
                                    <jet-nav-link
                                        class="inline"
                                        :href="
                                            route('calendars.show', [
                                                property.data.id,
                                                calendar.id,
                                            ])
                                        "
                                    >
                                        {{ calendar.attributes.name }}
                                    </jet-nav-link>
                                </th>

                                <td
                                    class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                                >
                                    <jet-nav-link
                                        class="inline"
                                        :href="
                                            route('calendars.edit', [
                                                property.data.id,
                                                calendar.id,
                                            ])
                                        "
                                    >
                                        <i
                                            class="fas fa-edit text-gray-800 mr-4"
                                        ></i>
                                    </jet-nav-link>

                                    <i
                                        class="fas fa-trash-alt text-gray-800 mr-4 cursor-pointer"
                                        @click="deleteCalendar(calendar.id)"
                                    ></i>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import JetNavLink from '@/Jetstream/NavLink'

export default {
    name: 'CalendarsTable.vue',
    components: {
        JetNavLink,
    },
    props: {
        property: {
            type: Object,
            default: () => {},
        },
        calendars: {
            type: Array,
            default: () => [],
        },
    },
    methods: {
        deleteCalendar(id) {
            if (!confirm('Are you sure want to remove this calendar?')) return
            this.$inertia.delete(
                route('calendars.destroy', [this.property.data.id, id]),
                {}
            )
        },
    },
}
</script>

<style scoped></style>
