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
                                    Bookings for:
                                    {{ $page.props.user.name }}
                                </h3>
                            </div>
                            <div
                                class="relative w-full px-4 max-w-full flex-grow flex-1 text-right"
                            >
                                <jet-nav-link
                                    :href="
                                        route(
                                            'user-bookings.create',
                                            $page.props.user.id
                                        )
                                    "
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
                                        Property
                                    </th>
                                    <th
                                        class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                    >
                                        Calendar
                                    </th>
                                    <th
                                        class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                    >
                                        Slot
                                    </th>
                                    <th
                                        class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                    >
                                        Date
                                    </th>
                                    <th
                                        class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                    >
                                        Purchased at
                                    </th>
                                    <th
                                        class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                    ></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(
                                        bookingSlot, bookingSlotIndex
                                    ) in bookingSlots.data"
                                    :key="bookingSlotIndex"
                                >
                                    <th
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-left"
                                    >
                                        {{
                                            bookingSlot.attributes.booking
                                                .attributes.property.attributes
                                                .name
                                        }}
                                    </th>
                                    <td
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                                    >
                                        {{
                                            bookingSlot.attributes.slot
                                                .attributes.calendar.attributes
                                                .name
                                        }}
                                    </td>
                                    <td
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                                    >
                                        {{
                                            bookingSlot.attributes.slot
                                                .attributes.start_time
                                        }}
                                        -
                                        {{
                                            bookingSlot.attributes.slot
                                                .attributes.end_time
                                        }}
                                    </td>
                                    <td
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                                    >
                                        {{
                                            moment(
                                                bookingSlot.attributes.booking
                                                    .attributes.date
                                            ).format('Do MMMM YYYY')
                                        }}
                                    </td>
                                    <td
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                                    >
                                        {{
                                            moment(
                                                bookingSlot.attributes.booking
                                                    .attributes.created_at
                                            ).format('Do MMMM YYYY')
                                        }}
                                    </td>
                                    <td
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                                    >
                                        <!--                                        <jet-nav-link-->
                                        <!--                                            class="inline"-->
                                        <!--                                            :href="-->
                                        <!--                                                route(-->
                                        <!--                                                    'properties.edit',-->
                                        <!--                                                    property.id-->
                                        <!--                                                )-->
                                        <!--                                            "-->
                                        <!--                                        >-->
                                        <!--                                            <i-->
                                        <!--                                                class="fas fa-edit text-gray-800 mr-4"-->
                                        <!--                                            ></i>-->
                                        <!--                                        </jet-nav-link>-->

                                        <i
                                            class="fas fa-trash-alt text-gray-800 mr-4 cursor-pointer"
                                            @click="deleteBooking(bookingSlot)"
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
import moment from 'moment'

export default {
    components: {
        AppLayout,
        JetNavLink,
    },
    props: {
        bookings: {
            type: Object,
            default: () => {},
        },
        bookingSlots: {
            type: Object,
            default: () => {},
        },
    },
    methods: {
        moment,
        deleteBooking(bookingSlot) {
            if (!confirm('Are you sure want to remove this booking?')) return
            this.$inertia.delete(
                route('user-bookings.destroy', {
                    userId: this.$page.props.user.id,
                    id: bookingSlot.attributes.booking.id,
                }),
                {
                    slot_id: bookingSlot.id,
                }
            )
        },
    },
}
</script>
