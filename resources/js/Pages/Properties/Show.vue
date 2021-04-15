<template>
    <app-layout>
        <calendars-table
            :property="property"
            :calendars="property.data.attributes.calendars"
        ></calendars-table>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import CalendarsTable from '@/Custom/CalendarsTable'

export default {
    components: {
        AppLayout,
        CalendarsTable,
    },
    props: {
        property: {
            type: Object,
            default: () => {},
        },
    },
}
</script>
