<template>
    <div>
        <label
            id="listbox-label"
            class="block text-sm font-medium text-gray-700"
        >
            <slot name="label"></slot>
        </label>
        <div class="mt-1 relative">
            <button
                type="button"
                aria-haspopup="listbox"
                :aria-expanded="isAriaExpanded"
                aria-labelledby="listbox-label"
                class="cursor-pointer relative w-full bg-white border border-gray-300 rounded-md shadow-sm pl-3 pr-10 py-2 text-left cursor-default focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                @click="handleSelectOpen"
            >
                <span class="flex items-center">
                    <span class="block truncate">
                        {{
                            selectedOption
                                ? selectedOption.label
                                : 'Filter by calendar'
                        }}
                    </span>
                </span>
                <span
                    class="ml-3 absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none"
                >
                    <!-- Heroicon name: selector -->
                    <svg
                        class="h-5 w-5 text-gray-400"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                        aria-hidden="true"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                            clip-rule="evenodd"
                        />
                    </svg>
                </span>
            </button>
            <div
                class="absolute mt-1 w-full rounded-md bg-white shadow-lg z-10"
                :class="!isAriaExpanded ? 'hidden' : ''"
            >
                <ul
                    tabindex="-1"
                    role="listbox"
                    aria-labelledby="listbox-label"
                    aria-activedescendant="listbox-item-3"
                    class="max-h-56 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm"
                >
                    <!--
                    Select option, manage highlight styles based on mouseenter/mouseleave and keyboard navigation.

                    Highlighted: "text-white bg-indigo-600", Not Highlighted: "text-gray-900"
                  -->
                    <li
                        v-for="(option, i) in options"
                        :id="`listbox-item-${option.id}`"
                        :key="i"
                        role="option"
                        class="text-gray-900 cursor-default select-none relative py-2 pl-3 pr-9 cursor-pointer hover:bg-gray-700"
                        @click="handleSelectClick(option)"
                    >
                        <div class="flex items-center">
                            <!-- <img
                              src="https://images.unsplash.com/photo-1491528323818-fdd1faba62cc?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80"
                              alt=""
                              class="flex-shrink-0 h-6 w-6 rounded-full"
                            /> -->
                            <!-- Selected: "font-semibold", Not Selected: "font-normal" -->
                            <span class="block font-normal truncate">
                                {{ option.label }}
                            </span>
                        </div>

                        <!--
                        Checkmark, only display for selected option.

                        Highlighted: "text-white", Not Highlighted: "text-indigo-600"
                      -->
                        <span
                            v-if="
                                selectedOption &&
                                option.id === selectedOption.id
                            "
                            class="absolute inset-y-0 right-0 flex items-center pr-4"
                        >
                            <!-- Heroicon name: check -->
                            <svg
                                class="h-5 w-5"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                                aria-hidden="true"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        options: {
            type: Array,
            required: true,
        },
        selectedOption: {
            type: Object,
            required: true,
        },
    },

    emits: ['selectChange'],

    data() {
        return {
            isAriaExpanded: false,
        }
    },

    methods: {
        toggleAriaExpanded() {
            this.isAriaExpanded = !this.isAriaExpanded
        },
        handleSelectOpen() {
            this.toggleAriaExpanded()
        },
        handleSelectClick(option) {
            this.toggleAriaExpanded()
            this.$emit('selectChange', option)
        },
    },
}
</script>

<style>
li:hover > div {
    color: white;
}
li:hover svg {
    color: white;
}
</style>
