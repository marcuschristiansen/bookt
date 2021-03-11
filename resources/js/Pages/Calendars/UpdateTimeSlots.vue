<template>
    <jet-form-section>
        <template #title> Schedule Timings </template>

        <template #description>
            Update your available time slots here
        </template>

        <template #form>
            <!-- Day -->
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="slot" value="Time slot" />
                <!--                <jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" autocomplete="name" />-->
                <jet-select
                    id="slot"
                    :selected-option="selectedDay"
                    class="mt-1 block w-full"
                    :options="days"
                    @selectChange="dayChanged"
                ></jet-select>
            </div>
            <div class="col-span-6 sm:col-span-4">
                <div
                    v-for="slot in slots"
                    :key="slot.id"
                    class="relative bg-green-700 p-4 mt-4 mb-4 rounded"
                >
                    <span
                        class="absolute text-white right-6 cursor-pointer"
                        @click="removeSlot(slot.id)"
                        >x</span
                    >
                    <p class="text-white text-center">
                        {{ slot.start_time }} - {{ slot.end_time }}
                    </p>
                    <p class="text-white text-sm text-center">
                        Max {{ slot.max_bookings }} bookings allowed for this
                        slot
                    </p>
                </div>

                <div class="flex items-end space-x-4">
                    <div class="">
                        <jet-label value="Start time" />
                        <vue-time-picker
                            v-model="newTimeSlot.start_time"
                        ></vue-time-picker>
                    </div>
                    <div class="">
                        <jet-label value="End time" />
                        <vue-time-picker
                            v-model="newTimeSlot.end_time"
                        ></vue-time-picker>
                    </div>
                    <div class="">
                        <jet-label value="Max Bookings" />
                        <jet-input
                            v-model="newTimeSlot.max_bookings"
                            type="number"
                        ></jet-input>
                    </div>
                </div>
                <div class="flex items-end flex-wrap space-x-4 mt-4">
                    <div>
                        <jet-label value="" />
                        <jet-secondary-button @click="addSlot"
                            >Add time slot</jet-secondary-button
                        >
                        <!--                        <jet-action-message-->
                        <!--                            v-if="$page.props.jetstream.flash.success"-->
                        <!--                            class="mr-3"-->
                        <!--                        >-->
                        <!--                            {{ $page.props.jetstream.flash.success }}-->
                        <!--                        </jet-action-message>-->
                    </div>
                </div>
            </div>
        </template>

        <template #actions> </template>
    </jet-form-section>
</template>

<script>
import JetFormSection from '@/Jetstream/FormSection'
import JetActionMessage from '@/Jetstream/ActionMessage'
import JetInput from '@/Jetstream/Input'
import JetLabel from '@/Jetstream/Label'
import JetSecondaryButton from '@/Jetstream/SecondaryButton'
import JetSelect from '@/Jetstream/Select'
import VueTimePicker from 'vue3-timepicker'

export default {
    components: {
        JetFormSection,
        JetActionMessage,
        JetInput,
        JetLabel,
        JetSecondaryButton,
        JetSelect,
        VueTimePicker,
    },

    props: {
        calendar: {
            type: Object,
            required: true,
        },
        errors: {
            type: Array,
            default: () => [],
        },
    },
    data() {
        return {
            days: [
                {
                    id: 1,
                    label: 'Monday',
                },
                {
                    id: 2,
                    label: 'Tuesday',
                },
                {
                    id: 3,
                    label: 'Wednesday',
                },
                {
                    id: 4,
                    label: 'Thursday',
                },
                {
                    id: 5,
                    label: 'Friday',
                },
                {
                    id: 6,
                    label: 'Saturday',
                },
                {
                    id: 7,
                    label: 'Sunday',
                },
            ],
            selectedDay: {
                id: 1,
                label: 'Monday',
            },
            slots: [],
            newTimeSlot: {
                id: false,
                calendar_id: '',
                day_id: '',
                start_time: '',
                end_time: '',
                max_bookings: 1,
            },
        }
    },

    mounted() {
        this.filterSlotsByDay()
    },

    methods: {
        dayChanged(day) {
            this.selectedDay = day
            this.filterSlotsByDay()
        },
        filterSlotsByDay() {
            this.slots = this.calendar.slots.filter(
                (slot) => slot.day_id === this.selectedDay.id
            )
        },
        removeSlot(id) {
            // confirm with user
            if (!confirm('Are you sure want to remove?')) return

            // delete the slot from the server
            this.$inertia.post(`/slots/${id}`, {
                preserveScroll: true,
                _method: 'DELETE',
            })

            // delete the object from the array
            this.slots = this.slots.filter((slot) => slot.id !== id)
        },
        addSlot() {
            if (!this.newTimeSlot.start_time || !this.newTimeSlot.end_time) {
                return
            }
            this.newTimeSlot.calendar_id = this.calendar.id
            this.newTimeSlot.day_id = this.selectedDay.id

            this.$inertia.post(`/slots/`, this.newTimeSlot)

            this.slots.push(this.newTimeSlot)
        },
    },
}
</script>

<style>
@import 'vue3-timepicker/dist/VueTimepicker.css';
</style>
