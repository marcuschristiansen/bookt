<template>
    <component :is="user.current_team_role"></component>
</template>

<script>
import User from '@/Pages/Bookings/Index/User'
import TeamAdmin from '@/Pages/Bookings/Index/TeamAdmin'

export default {
    components: {
        User,
        TeamAdmin,
    },
    props: ['user'],
}
</script>
