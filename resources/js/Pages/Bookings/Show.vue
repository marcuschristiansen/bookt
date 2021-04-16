<template>
    <app-layout>
        <div class="flex flex-wrap mt-4">
            <div class="w-full mb-12 xl:mb-0 px-4">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded"
                >
                    <div class="rounded-t mb-0 px-4 py-3 border-0">
                        <h3
                            class="text-2xl font-semibold text-base text-blueGray-700 my-4"
                        >
                            Booking #{{ booking.data.id }}
                        </h3>
                        <div class="flex flex-wrap items-center">
                            <div
                                class="relative w-full px-4 max-w-full flex-grow flex-1 text-right"
                            >
                                <!--                                <jet-nav-link-->
                                <!--                                    :href="route('properties.create')"-->
                                <!--                                    class="bg-indigo-500 text-white active:bg-indigo-600 hover:bg-indigo-400 text-xs font-bold uppercase px-3 py-1 rounded outline-none focus:outline-none mr-1 mb-1 inline hover:text-white"-->
                                <!--                                    type="button"-->
                                <!--                                    style="transition: all 0.15s ease"-->
                                <!--                                >-->
                                <!--                                    Create New-->
                                <!--                                </jet-nav-link>-->
                            </div>
                        </div>
                    </div>
                    <div class="block w-full overflow-x-auto p-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-gray-600">Name</p>
                                <p class="text-gray-800">
                                    {{
                                        booking.data.attributes.user.attributes
                                            .name
                                    }}
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-600">Email</p>
                                <p class="text-gray-800 underline">
                                    <a
                                        :href="`mailto:${booking.data.attributes.user.attributes.email}`"
                                        >{{
                                            booking.data.attributes.user
                                                .attributes.email
                                        }}</a
                                    >
                                    {{}}
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-600">Property</p>
                                <p class="text-gray-800">
                                    {{
                                        booking.data.attributes.property
                                            .attributes.name
                                    }}
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-600">Slots</p>
                                <ul>
                                    <li
                                        v-for="(slot, index) in booking.data
                                            .attributes.slots"
                                        :key="index"
                                    >
                                        {{
                                            moment(booking.data.date).format(
                                                'Do MMMM YYYY'
                                            )
                                        }}
                                        |
                                        {{
                                            slot.attributes.calendar.attributes
                                                .name
                                        }}
                                        @ {{ slot.attributes.start_time }} -
                                        {{ slot.attributes.end_time }}
                                    </li>
                                </ul>
                                <p class="text-gray-800"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import moment from 'moment'

export default {
    name: 'Show.vue',
    components: {
        AppLayout,
    },
    props: ['booking'],
    methods: {
        moment,
    },
}
</script>

<style scoped></style>
