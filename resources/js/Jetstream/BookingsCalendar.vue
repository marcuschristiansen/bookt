<template>
    <div
        class="bg-white py-12 shadow-xl sm:rounded-lg max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8"
    >
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex flex-wrap">
                <h1 class="text-2xl sm:mr-6">
                    {{ months[dates[0].getMonth()] }}
                    {{ dates[0].getFullYear() }}
                </h1>
                <div>
                    <button
                        class="inline-flex items-center px-4 mr-2 py-2 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest focus:outline-none"
                        @click="startDay = 0"
                    >
                        Today
                    </button>
                    <button
                        class="inline-flex items-center px-4 mx-2 py-2 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest focus:outline-none"
                        @click="startDay -= visibleDays"
                    >
                        <i class="fa fa-chevron-left" aria-hidden="true"></i>
                    </button>
                    <button
                        class="inline-flex items-center px-4 mx-2 py-2 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest focus:outline-none"
                        @click="startDay += visibleDays"
                    >
                        <i class="fa fa-chevron-right" aria-hidden="true"></i>
                    </button>
                </div>
            </div>

            <div
                id="head"
                class="flex justify-between flex-wrap sm:flex-nowrap py-8"
            >
                <div
                    v-for="(date, dayIndex) in dates"
                    :key="dayIndex"
                    class="text-center w-full border-b-1 sm:h-96 overflow-y-auto"
                    :class="
                        dayIndex === 0 ? '' : 'sm:border-l sm:border-gray-200'
                    "
                >
                    <div class="mb-2">{{ days[date.getDay()] }}</div>
                    <span
                        :class="
                            isToday(date)
                                ? 'bg-gray-800 py-2 px-2 rounded-full text-white'
                                : ''
                        "
                        >{{ date.getDate() }}</span
                    >
                    <div
                        v-for="(item, index) in bookings"
                        :key="index"
                        class="p-2"
                    >
                        <button
                            v-if="
                                item.attributes.date ===
                                `${date.getFullYear()}-${(
                                    '0' +
                                    (date.getMonth() + 1)
                                ).slice(-2)}-${date.getDate()}`
                            "
                            class="bg-gray-800 text-white rounded p-2 block w-full"
                            @click="openBooking(index)"
                        >
                            <span class="block">
                                {{ item.attributes.user.attributes.name }}
                            </span>
                            <span class="text-sm block">
                                {{ item.attributes.slot.attributes.start_time }}
                                -
                                {{ item.attributes.slot.attributes.end_time }}
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <jet-dialog-modal
            v-if="activeBooking"
            :show="viewingBooking"
            @close="closeModal"
        >
            <template #title>
                {{ activeBooking.attributes.user.attributes.name }}
            </template>

            <template #content>
                <div class="mt-4">
                    <div>
                        <span>Calendar: </span>
                        <span>{{
                            activeBooking.attributes.slot.attributes.calendar
                                .attributes.name
                        }}</span>
                    </div>
                    <div>
                        <span>Date: </span>
                        <span>{{
                            moment(activeBooking.attributes.date).format('LL')
                        }}</span>
                    </div>
                    <div>
                        <span>Time: </span>
                        <span
                            >{{
                                activeBooking.attributes.slot.attributes
                                    .start_time
                            }}
                            -
                            {{
                                activeBooking.attributes.slot.attributes
                                    .end_time
                            }}</span
                        >
                    </div>
                </div>
            </template>

            <template #footer>
                <jet-secondary-button @click.native="closeModal">
                    Close
                </jet-secondary-button>
                <jet-button
                    class="ml-2"
                    @click.native="deleteBooking(activeBooking)"
                >
                    Delete Booking
                </jet-button>
            </template>
        </jet-dialog-modal>
    </div>
</template>

<script>
import moment from 'moment'
import JetDialogModal from '@/Jetstream/DialogModal'
import JetButton from '@/Jetstream/Button'
import JetSecondaryButton from '@/Jetstream/SecondaryButton'
export default {
    components: {
        JetDialogModal,
        JetButton,
        JetSecondaryButton,
    },
    props: ['bookings'],
    emits: ['bookingDelete'],
    data() {
        return {
            visibleDays: 7,
            startDay: 0,
            days: [
                'Sunday',
                'Monday',
                'Tuesday',
                'Wednesday',
                'Thursday',
                'Friday',
                'Saturday',
            ],
            months: [
                'January',
                'February',
                'March',
                'April',
                'May',
                'June',
                'July',
                'August',
                'September',
                'October',
                'November',
                'December',
            ],
            d: new Date(),
            viewingBooking: false,
            activeBooking: null,
        }
    },
    computed: {
        dates() {
            const days = []
            for (
                let i = this.startDay;
                i < this.startDay + this.visibleDays;
                i++
            ) {
                let date = new Date()
                date.setDate(date.getDate() + i)
                days.push(date)
            }
            return days
        },
    },
    methods: {
        moment,
        isToday(date) {
            const today = new Date()
            return (
                date.getDate() === today.getDate() &&
                date.getMonth() === today.getMonth() &&
                date.getFullYear() === today.getFullYear()
            )
        },
        closeModal() {
            this.viewingBooking = false
            this.activeBooking = null
        },
        openBooking(bookingIndex) {
            this.activeBooking = this.bookings[bookingIndex]
            this.viewingBooking = true
        },
        deleteBooking(booking) {
            // confirm with user
            if (!confirm('Are you sure want to remove this booking?')) return

            this.$emit('bookingDelete', booking.id)

            this.closeModal()
        },
    },
}
</script>

<style lang="scss" scoped></style>
