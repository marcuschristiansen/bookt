<template>
    <app-layout>
        <div class="flex flex-wrap mt-4">
            <div class="w-full mb-12 xl:mb-0 px-4">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded"
                >
                    <div class="rounded-t mb-0 px-4 py-3 border-0">
                        <h3
                            class="font-semibold text-base text-blueGray-700 my-4"
                        >
                            Bookings for
                            {{ moment(date).format('Do MMMM YYYY') }}
                        </h3>
                        <div class="flex flex-wrap items-center">
                            <div class="mr-4">
                                <jet-label value="Select date" />
                                <date-picker
                                    v-model="selectedDate"
                                    class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full"
                                ></date-picker>
                            </div>
                            <div class="mr-4 flex-auto">
                                <jet-label value="Select property" />
                                <jet-select
                                    :options="properties"
                                    :selected-option="selectedProperty"
                                    @selectChange="propertyChanged"
                                ></jet-select>
                            </div>

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
                                        <jet-nav-link
                                            class="inline"
                                            :href="
                                                route(
                                                    'bookings.show',
                                                    bookingSlot.attributes
                                                        .booking_id
                                                )
                                            "
                                        >
                                            {{
                                                bookingSlot.attributes.booking
                                                    .attributes.user.attributes
                                                    .name
                                            }}
                                        </jet-nav-link>
                                    </th>
                                    <td
                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                                    >
                                        {{
                                            bookingSlot.attributes.booking
                                                .attributes.property.attributes
                                                .name
                                        }}
                                    </td>
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
                                            @click="
                                                deleteBookingSlot(
                                                    bookingSlot.id
                                                )
                                            "
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
import JetSelect from '@/Jetstream/Select'
import JetButton from '@/Jetstream/Button'
import JetNavLink from '@/Jetstream/NavLink'
import moment from 'moment'
import DatePicker from 'vue3-datepicker'
import { ref } from 'vue'
import JetLabel from '@/Jetstream/Label'

export default {
    components: {
        AppLayout,
        DatePicker,
        JetLabel,
        JetSelect,
        JetButton,
        JetNavLink,
    },
    props: ['bookings', 'bookingSlots', 'properties', 'date', 'property'],
    data() {
        return {
            selectedProperty: this.properties.filter(
                (property) => property.id === this.property
            )[0],
            selectedDate: ref(new Date(this.date)),
        }
    },
    watch: {
        selectedDate() {
            this.fetchBookings()
        },
    },
    methods: {
        moment,
        fetchBookings() {
            const date = moment(this.selectedDate).format('YYYY-MM-DD')
            this.$inertia.visit(
                `/bookings?date=${date}&property=${this.selectedProperty.id}`
            )
        },
        propertyChanged(property) {
            this.selectedProperty = property
            this.fetchBookings()
        },
        deleteBookingSlot(id) {
            if (!confirm('Are you sure want to remove this booking slot?'))
                return
            this.$inertia.delete(route('bookings.destroyBookingSlot', id))
        },
    },
}
</script>

<style scoped>
.v3dp__datepicker {
    display: inline-block;
}
</style>
