<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ calendar['name'] }} Calendar
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <div class="mt-10">
                    <update-calendar-form
                        :calendar="calendar"
                    ></update-calendar-form>
                </div>

                <div class="mt-10">
                    <update-time-slots :calendar="calendar"></update-time-slots>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import UpdateCalendarForm from './UpdateCalendarForm'
import UpdateTimeSlots from './UpdateTimeSlots'

export default {
    components: {
        AppLayout,
        UpdateCalendarForm,
        UpdateTimeSlots,
    },
    computed: {
        calendar() {
            return this.$page['props']['calendar']
        },
    },
}
</script>
