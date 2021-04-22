<template>
    <app-layout>
        <div class="flex flex-wrap mt-4">
            <div class="w-full mb-12 xl:mb-0 px-4">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded"
                >
                    <div class="rounded-t mb-0 px-4 py-3 border-0">
                        <h3 class="font-semibold text-base text-blueGray-700">
                            New booking
                        </h3>
                        <!-- Booking Errors -->
                        <div class="my-4">
                            <div
                                v-if="form.errors.slot_id"
                                class="text-white px-6 py-4 border-0 rounded relative mb-4 bg-red-500"
                            >
                                <span
                                    class="text-xl inline-block mr-5 align-middle"
                                >
                                    <i class="fas fa-bell"></i>
                                </span>
                                <span class="inline-block align-middle mr-8">
                                    {{ form.errors.slot_id }}
                                </span>
                            </div>
                            <form
                                v-if="properties.length !== 0"
                                @submit.prevent="save"
                            >
                                <div class="relative w-full mb-3">
                                    <jet-label
                                        for="property"
                                        value="Property"
                                    />
                                    <!--                <jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" autocomplete="name" />-->
                                    <jet-select
                                        id="property"
                                        :selected-option="selectedProperty[0]"
                                        class="mt-1 block w-full"
                                        :options="properties"
                                        @selectChange="propertyChanged"
                                    ></jet-select>
                                </div>
                                <div
                                    v-if="selectedProperty[0]"
                                    class="relative w-full mb-3"
                                >
                                    <jet-label
                                        for="calendar"
                                        value="Calendar"
                                    />
                                    <!--                <jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" autocomplete="name" />-->
                                    <jet-select
                                        id="calendar"
                                        :selected-option="
                                            selectedProperty[0].calendars[0]
                                        "
                                        class="mt-1 block w-full"
                                        :options="calendars"
                                        @selectChange="calendarChanged"
                                    ></jet-select>
                                </div>
                                <div class="relative w-full mb-3">
                                    <slots-calendar
                                        :slots="
                                            selectedProperty[0].calendars[0]
                                                .slots
                                        "
                                        @slotSelected="saveBooking"
                                    ></slots-calendar>
                                </div>
                            </form>
                            <h1 v-if="properties.length === 0">
                                You do not have any active memberships.
                            </h1>
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
            ),
        }
    },
    computed: {
        calendars() {
            return this.selectedProperty[0].calendars
        },
    },
    methods: {
        moment,
        saveBooking({ slot, date }) {
            if (
                !confirm(
                    `Please confirm your booking for ${this.selectedProperty[0].calendars[0].label} (${slot.start_time} - ${slot.end_time} at ${this.selectedProperty[0].label})`
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
            this.selectedProperty = [property]
            this.calendarChanged(property[0].calendars[0])
        },
        calendarChanged(calendar) {
            this.selectedProperty[0].calendars[0] = calendar
        },
    },
}
</script>
