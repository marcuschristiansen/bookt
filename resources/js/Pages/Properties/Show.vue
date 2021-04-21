<template>
    <app-layout>
        <calendars-table
            :property="property"
            :calendars="property.data.attributes.calendars"
        ></calendars-table>
        <users-table :property="property" :users="users"></users-table>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import CalendarsTable from '@/Custom/CalendarsTable'
import UsersTable from '@/Custom/UsersTable'

export default {
    components: {
        AppLayout,
        CalendarsTable,
        UsersTable,
    },
    props: {
        property: {
            type: Object,
            default: () => {},
        },
        users: {
            type: Array,
            default: () => [],
        },
    },
}
</script>
