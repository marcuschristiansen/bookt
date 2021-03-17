<template>
    <app-layout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Bookings
                </h2>
            </div>
        </template>

        <div class="my-8">
            <JetBookingsCalendar
                :bookings="bookings.data"
                @bookingDelete="deleteBooking"
            />
        </div>

        <!--        <div v-if="calendars.length && calendars" class="py-12">-->
        <!--            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">-->
        <!--                <date-picker-->
        <!--                    v-model="selectedDate"-->
        <!--                    class="inline-block mr-4"-->
        <!--                ></date-picker>-->
        <!--                <jet-select-->
        <!--                    id="slot"-->
        <!--                    :selected-option="-->
        <!--                        calendars.find(({ id }) => id === calendar)-->
        <!--                    "-->
        <!--                    class="min-w-max"-->
        <!--                    :options="calendars"-->
        <!--                    @selectChange="calendarChanged"-->
        <!--                ></jet-select>-->
        <!--                <div-->
        <!--                    class="bg-white overflow-hidden shadow-xl sm:rounded-lg mt-8"-->
        <!--                >-->
        <!--                    <table class="min-w-full divide-y divide-gray-200">-->
        <!--                        <thead class="bg-gray-50">-->
        <!--                            <tr>-->
        <!--                                <th-->
        <!--                                    scope="col"-->
        <!--                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"-->
        <!--                                >-->
        <!--                                    Name-->
        <!--                                </th>-->
        <!--                                <th-->
        <!--                                    scope="col"-->
        <!--                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"-->
        <!--                                >-->
        <!--                                    Date-->
        <!--                                </th>-->
        <!--                                <th-->
        <!--                                    scope="col"-->
        <!--                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"-->
        <!--                                >-->
        <!--                                    Time-->
        <!--                                </th>-->
        <!--                                <th-->
        <!--                                    scope="col"-->
        <!--                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"-->
        <!--                                >-->
        <!--                                    Calendar-->
        <!--                                </th>-->
        <!--                                <th-->
        <!--                                    scope="col"-->
        <!--                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"-->
        <!--                                ></th>-->
        <!--                                &lt;!&ndash;                                <th scope="col" class="relative px-6 py-3">&ndash;&gt;-->
        <!--                                &lt;!&ndash;                                    <span class="sr-only">Actions</span>&ndash;&gt;-->
        <!--                                &lt;!&ndash;                                </th>&ndash;&gt;-->
        <!--                            </tr>-->
        <!--                        </thead>-->
        <!--                        <tbody class="bg-white divide-y divide-gray-200">-->
        <!--                            <tr-->
        <!--                                v-for="booking in bookings.data"-->
        <!--                                :key="booking.id"-->
        <!--                            >-->
        <!--                                <td class="px-6 py-4 whitespace-nowrap">-->
        <!--                                    <div class="flex items-center">-->
        <!--                                        <div>-->
        <!--                                            <div-->
        <!--                                                class="text-sm font-medium text-gray-900"-->
        <!--                                            >-->
        <!--                                                {{-->
        <!--                                                    booking.attributes.user-->
        <!--                                                        .attributes.name-->
        <!--                                                }}-->
        <!--                                            </div>-->
        <!--                                        </div>-->
        <!--                                    </div>-->
        <!--                                </td>-->
        <!--                                <td class="px-6 py-4 whitespace-nowrap">-->
        <!--                                    <div class="flex items-center">-->
        <!--                                        <div>-->
        <!--                                            <div-->
        <!--                                                class="text-sm font-medium text-gray-900"-->
        <!--                                            >-->
        <!--                                                {{-->
        <!--                                                    formatDate(-->
        <!--                                                        booking.attributes.date,-->
        <!--                                                        'LL'-->
        <!--                                                    )-->
        <!--                                                }}-->
        <!--                                            </div>-->
        <!--                                        </div>-->
        <!--                                    </div>-->
        <!--                                </td>-->
        <!--                                <td class="px-6 py-4 whitespace-nowrap">-->
        <!--                                    <div class="flex items-center">-->
        <!--                                        <div>-->
        <!--                                            <div-->
        <!--                                                class="text-sm font-medium text-gray-900"-->
        <!--                                            >-->
        <!--                                                {{-->
        <!--                                                    booking.attributes.slot-->
        <!--                                                        .attributes.start_time-->
        <!--                                                }}-->
        <!--                                                - -->
        <!--                                                {{-->
        <!--                                                    booking.attributes.slot-->
        <!--                                                        .attributes.end_time-->
        <!--                                                }}-->
        <!--                                            </div>-->
        <!--                                        </div>-->
        <!--                                    </div>-->
        <!--                                </td>-->
        <!--                                <td class="px-6 py-4 whitespace-nowrap">-->
        <!--                                    <div class="flex items-center">-->
        <!--                                        <div>-->
        <!--                                            <div-->
        <!--                                                class="text-sm font-medium text-gray-900"-->
        <!--                                            >-->
        <!--                                                {{-->
        <!--                                                    booking.attributes.slot-->
        <!--                                                        .attributes.calendar-->
        <!--                                                        .attributes.name-->
        <!--                                                }}-->
        <!--                                            </div>-->
        <!--                                        </div>-->
        <!--                                    </div>-->
        <!--                                </td>-->
        <!--                                <td class="px-6 py-4 whitespace-nowrap">-->
        <!--                                    <div class="flex items-center">-->
        <!--                                        <div>-->
        <!--                                            <div-->
        <!--                                                class="text-sm font-medium text-gray-900"-->
        <!--                                            >-->
        <!--                                                <JetButton-->
        <!--                                                    @click="-->
        <!--                                                        deleteBooking(-->
        <!--                                                            booking.id-->
        <!--                                                        )-->
        <!--                                                    "-->
        <!--                                                    >Delete</JetButton-->
        <!--                                                >-->
        <!--                                            </div>-->
        <!--                                        </div>-->
        <!--                                    </div>-->
        <!--                                </td>-->
        <!--                            </tr>-->
        <!--                            &lt;!&ndash; More items... &ndash;&gt;-->
        <!--                        </tbody>-->
        <!--                    </table>-->
        <!--                </div>-->
        <!--            </div>-->
        <!--        </div>-->
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import JetSelect from '@/Jetstream/Select'
import JetButton from '@/Jetstream/Button'
import JetNavLink from '@/Jetstream/NavLink'
import moment from 'moment'
import DatePicker from 'vue3-datepicker'
import JetBookingsCalendar from '@/Jetstream/BookingsCalendar'

import { ref } from 'vue'

export default {
    components: {
        AppLayout,
        DatePicker,
        JetSelect,
        JetButton,
        JetNavLink,
        JetBookingsCalendar,
    },
    props: ['date', 'calendar', 'bookings'],
    data() {
        return {
            selectedCalendar: this.calendar,
            selectedDate: ref(new Date(this.date)),
        }
    },
    computed: {
        calendars() {
            const calendars = this.$page['props']['user']['current_team'][
                'calendars'
            ]

            return calendars.map((calendar) => {
                const container = {}

                container['id'] = calendar.id
                container['label'] = calendar.name

                return container
            })
        },
    },
    watch: {
        selectedDate() {
            this.fetchBookings()
        },
    },
    methods: {
        formatDate(date, format) {
            return moment(date).format(format)
        },
        fetchBookings() {
            const date = moment(this.selectedDate).format('YYYY-MM-DD')
            this.$inertia.visit(
                `/bookings?date=${date}&calendar=${this.selectedCalendar}`
            )
        },
        calendarChanged(calendar) {
            this.selectedCalendar = calendar.id
            this.fetchBookings()
        },
        deleteBooking(id) {
            // delete the slot from the server
            this.$inertia.post(`/bookings/${id}`, {
                preserveScroll: true,
                _method: 'DELETE',
            })
        },
    },
}
</script>

<style>
.v3dp__datepicker {
    display: inline-block;
}
</style>
