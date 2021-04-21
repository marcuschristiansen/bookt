<template>
    <app-layout>
        <div class="flex flex-wrap mt-4">
            <div class="w-full mb-12 xl:mb-0 px-4">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded"
                >
                    <div class="rounded-t mb-0 px-4 py-3 border-0">
                        <h1>New booking</h1>
                        <div class="my-4">
                            <form @submit.prevent="save">
                                <div class="relative w-full mb-3">
                                    <jet-label
                                        for="property"
                                        value="Property"
                                    />
                                    <!--                <jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" autocomplete="name" />-->
                                    <jet-select
                                        id="property"
                                        :selected-option="selectedProperty"
                                        class="mt-1 block w-full"
                                        :options="properties"
                                        @selectChange="propertyChanged"
                                    ></jet-select>
                                </div>
                                <div
                                    v-if="selectedProperty"
                                    class="relative w-full mb-3"
                                >
                                    <jet-label
                                        for="calendar"
                                        value="Calendar"
                                    />
                                    <!--                <jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" autocomplete="name" />-->
                                    <jet-select
                                        id="calendar"
                                        :selected-option="selectedCalendar"
                                        class="mt-1 block w-full"
                                        :options="calendars"
                                        @selectChange="calendarChanged"
                                    ></jet-select>
                                </div>
                                <div class="relative w-full mb-3">
                                    <slots-calendar
                                        :slots="selectedCalendar.slots"
                                        @slotSelected="saveBooking"
                                    ></slots-calendar>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import JetInput from '@/Jetstream/Input'
import JetCheckbox from '@/Jetstream/Checkbox'
import JetInputError from '@/Jetstream/InputError'
import JetLabel from '@/Jetstream/Label'
import JetButton from '@/Jetstream/Button'
import JetSelect from '@/Jetstream/Select'
import SlotsCalendar from '@/Custom/SlotsCalendar'
import moment from 'moment'

export default {
    components: {
        AppLayout,
        JetInput,
        JetCheckbox,
        JetInputError,
        JetLabel,
        JetButton,
        JetSelect,
        SlotsCalendar,
    },
    props: {
        properties: {
            type: Array,
            default: () => [],
        },
        property: {
            type: Object,
            default: () => {},
        },
    },
    data() {
        return {
            form: this.$inertia.form({
                slot_id: undefined,
                date: undefined,
            }),
            selectedProperty: this.properties.filter(
                (property) => property.id === this.property.id
            )[0],
            selectedCalendar: this.properties.filter(
                (property) => property.id === this.property.id
            )[0].calendars[0],
        }
    },
    computed: {
        calendars() {
            return this.selectedProperty.calendars
        },
    },
    methods: {
        moment,
        saveBooking({ slot, date }) {
            if (
                !confirm(
                    `Please confirm your booking for ${this.selectedCalendar.label} (${slot.start_time} - ${slot.end_time} at ${this.selectedProperty.label})`
                )
            )
                return
            this.form.slot_id = slot.id
            this.form.date = this.moment(date).format('YYYY-MM-DD')
            this.form.post(
                route('user-bookings.store', this.$page.props.user.id),
                {
                    errorBag: 'save',
                    preserveScroll: true,
                }
            )
        },
        propertyChanged(property) {
            this.selectedProperty = property
            this.calendarChanged(property.calendars[0])
        },
        calendarChanged(calendar) {
            this.selectedCalendar = calendar
        },
    },
}
</script>
