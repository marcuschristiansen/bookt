<template>
    <input
        ref="input"
        v-mask="dataMask"
        :type="type"
        class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
        :value="modelValue"
        :placeholder="placeholder"
        @input="$emit('update:modelValue', $event.target.value)"
    />
</template>

<script>
import { mask } from 'vue-the-mask'

export default {
    directives: { mask },
    props: {
        modelValue: {
            type: [String, Number],
            default: '',
        },
        type: {
            type: String,
            default: 'text',
        },
        dataMask: {
            type: String,
            default: '',
        },
        placeholder: {
            type: String,
            default: '',
        },
    },

    emits: ['update:modelValue'],

    methods: {
        focus() {
            this.$refs.input.focus()
        },
    },
}
</script>
