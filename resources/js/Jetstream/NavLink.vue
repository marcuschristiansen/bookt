<template>
    <inertia-link :href="href" :class="classes">
        <slot></slot>
    </inertia-link>
</template>

<script>
export default {
    props: ['href', 'active'],

    computed: {
        classes() {
            return this.active
                ? 'text-gray-500 hover:text-gray-800 text-xs uppercase py-3 font-bold block'
                : 'text-blueGray-700 hover:text-blueGray-500 text-xs uppercase py-3 font-bold block'
        },
    },
}
</script>
