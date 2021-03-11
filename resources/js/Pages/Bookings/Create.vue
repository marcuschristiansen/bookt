<template>
    <app-layout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    New Booking
                </h2>
            </div>
        </template>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <jet-form-section>
                <template #title> New Booking </template>
                <template #description> Book a new timeslot here </template>
                <template #form>
                    <div class="col-span-6 sm:col-span-4">
                        <JetLabel>Pick a date</JetLabel>
                        <date-picker
                            v-model="selectedDate"
                            class="inline-block mr-4"
                        ></date-picker>
                    </div>
                    <div class="col-span-6 sm:col-span-4">
                        <JetLabel>Pick a calendar</JetLabel>
                        <jet-select
                            id="slot"
                            :selected-option="
                                calendars.find(({ id }) => id === calendar)
                            "
                            class="min-w-max"
                            :options="calendars"
                            @selectChange="calendarChanged"
                        ></jet-select>
                    </div>
                    <div v-if="slots" class="col-span-6 sm:col-span-4">
                        <JetLabel>Pick a slot</JetLabel>
                        <div class="flex flex-wrap">
                            <div
                                v-for="slot in slots.data"
                                :key="slot.id"
                                class="p-2 pl-0"
                            >
                                <jet-button
                                    class="space-x-2"
                                    @click="saveBooking(slot)"
                                >
                                    {{ slot.attributes.start_time }}
                                    -
                                    {{ slot.attributes.end_time }}
                                </jet-button>
                            </div>
                        </div>
                    </div>
                </template>
            </jet-form-section>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import JetFormSection from '@/Jetstream/FormSection'
import moment from 'moment'
import DatePicker from 'vue3-datepicker'
import JetLabel from '@/Jetstream/Label'
import JetSelect from '@/Jetstream/Select'
import JetButton from '@/Jetstream/Button'
import { ref } from 'vue'

export default {
    components: {
        AppLayout,
        DatePicker,
        JetLabel,
        JetSelect,
        JetButton,
        JetFormSection,
    },
    props: ['calendar', 'date', 'slots'],
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
            this.fetchSlots()
        },
    },
    methods: {
        formatDate(date, format) {
            return moment(date).format(format)
        },
        calendarChanged(calendar) {
            this.selectedCalendar = calendar.id
            this.fetchSlots()
        },
        fetchSlots() {
            const date = moment(this.selectedDate).format('YYYY-MM-DD')
            this.$inertia.get(
                `/bookings/create?calendar=${this.selectedCalendar}&date=${date}`
            )
        },
        saveBooking(slot) {
            // confirm with user
            if (
                !confirm(
                    `You are about to book the ${slot.attributes.start_time} slot. Click to confirm`
                )
            )
                return

            this.$inertia.post(`/bookings/`, {
                slot_id: slot.id,
                date: this.formatDate(this.selectedDate, 'YYYY-MM-DD'),
            })
        },
    },
}
</script>

<style scoped></style>
