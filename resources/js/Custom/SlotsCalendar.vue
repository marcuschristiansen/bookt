<template>
    <div class="bg-white py-12 sm:rounded-lg mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="mx-auto px-6 lg:px-8">
            <div class="flex">
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
                    <div class="mb-4"></div>
                    <div
                        v-for="(slot, index) in slotsByDay(slots, date)"
                        :key="index"
                        class="p-2"
                    >
                        <button
                            :id="`slot-${date.getDay()}-${slot.id}-${
                                slot.day_id
                            }`"
                            class="bg-gray-800 text-white rounded p-2 block w-full"
                            @click="selectSlot(slot, date)"
                        >
                            <span class="text-sm block">
                                {{ slot.start_time }}
                                -
                                {{ slot.end_time }}
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
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
    props: {
        slots: {
            type: Array,
            default: () => [],
        },
    },
    emits: ['slotSelected'],
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
        slotsByDay(slots, date) {
            return slots.filter((slot) => {
                return this.days[slot.day_id] === this.days[date.getDay()]
            })
        },
        selectSlot(slot, date) {
            this.$emit('slotSelected', { slot: slot, date: date })
        },
    },
}
</script>

<style lang="scss" scoped></style>
