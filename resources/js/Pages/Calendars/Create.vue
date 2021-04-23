<template>
    <app-layout>
        <div class="flex flex-wrap mt-4">
            <div class="w-full mb-12 xl:mb-0 px-4">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded"
                >
                    <div class="rounded-t mb-0 px-4 py-3 border-0">
                        <h1>Create new calendar</h1>
                        <div class="my-4">
                            <form @submit.prevent="save">
                                <div class="relative w-full mb-3">
                                    <jet-label for="name" value="Name" />
                                    <jet-input
                                        id="name"
                                        v-model="form.name"
                                        type="text"
                                        class="mt-1 block w-full"
                                        autocomplete="name"
                                    />
                                    <jet-input-error
                                        :message="form.errors.name"
                                        class="mt-2"
                                    />
                                </div>
                                <!-- Tabs -->
                                <div class="relative w-full mb-3">
                                    <div class="flex flex-wrap">
                                        <div class="w-full">
                                            <jet-label value="Slots" />
                                            <ul
                                                class="flex mb-0 list-none flex-col sm:flex-row pt-3 pb-4 flex-row"
                                            >
                                                <li
                                                    v-for="(
                                                        slot, slotIndex
                                                    ) in form.slots"
                                                    :key="slotIndex"
                                                    class="-mb-px mr-2 last:mr-0 flex-auto text-center cursor-pointer"
                                                >
                                                    <a
                                                        class="text-xs font-bold uppercase px-5 py-3 shadow-lg rounded block leading-normal"
                                                        :class="{
                                                            'text-gray-800 bg-white':
                                                                openTab !==
                                                                slotIndex,
                                                            'text-white bg-gray-800':
                                                                openTab ===
                                                                slotIndex,
                                                        }"
                                                        @click="
                                                            toggleTabs(
                                                                slotIndex
                                                            )
                                                        "
                                                    >
                                                        {{ slot.day }}
                                                    </a>
                                                </li>
                                            </ul>
                                            <div
                                                class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 overflow-x-auto"
                                            >
                                                <div
                                                    class="px-4 py-5 flex-auto"
                                                >
                                                    <div
                                                        class="tab-content tab-space"
                                                    >
                                                        <div
                                                            v-for="(
                                                                slot, slotIndex
                                                            ) in form.slots"
                                                            :key="slotIndex"
                                                            :class="{
                                                                hidden:
                                                                    openTab !==
                                                                    slotIndex,
                                                                block:
                                                                    openTab ===
                                                                    slotIndex,
                                                            }"
                                                        >
                                                            <!-- Slots -->
                                                            <table
                                                                class="items-center w-full bg-transparent border-collapse"
                                                            >
                                                                <thead>
                                                                    <tr>
                                                                        <th
                                                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                                                        >
                                                                            Start
                                                                            time
                                                                        </th>
                                                                        <th
                                                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                                                        >
                                                                            End
                                                                            time
                                                                        </th>
                                                                        <th
                                                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                                                        >
                                                                            Max
                                                                            bookings
                                                                        </th>
                                                                        <th
                                                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                                                        >
                                                                            Cost
                                                                        </th>
                                                                        <th
                                                                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                                                                        ></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr
                                                                        v-for="(
                                                                            item,
                                                                            itemIndex
                                                                        ) in slot.items"
                                                                        :key="
                                                                            itemIndex
                                                                        "
                                                                    >
                                                                        <td
                                                                            class="border-t-0 px-6 align-top border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                                                                        >
                                                                            <jet-masked-input
                                                                                :id="`start-time-${slotIndex}-${itemIndex}`"
                                                                                v-model="
                                                                                    form
                                                                                        .slots[
                                                                                        slotIndex
                                                                                    ]
                                                                                        .items[
                                                                                        itemIndex
                                                                                    ]
                                                                                        .start_time
                                                                                "
                                                                                type="text"
                                                                                class="mt-1 block w-full"
                                                                                autocomplete="start-time"
                                                                                :data-mask="'##:##'"
                                                                                :placeholder="'hh:mm'"
                                                                            />
                                                                            <jet-input-error
                                                                                :message="
                                                                                    form
                                                                                        .errors[
                                                                                        `slots.${slotIndex}.items.${itemIndex}.start_time`
                                                                                    ]
                                                                                "
                                                                                class="mt-2"
                                                                            />
                                                                        </td>
                                                                        <td
                                                                            class="border-t-0 px-6 align-top border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                                                                        >
                                                                            <jet-masked-input
                                                                                :id="`end-time-${slotIndex}-${itemIndex}`"
                                                                                v-model="
                                                                                    form
                                                                                        .slots[
                                                                                        slotIndex
                                                                                    ]
                                                                                        .items[
                                                                                        itemIndex
                                                                                    ]
                                                                                        .end_time
                                                                                "
                                                                                type="text"
                                                                                class="mt-1 block w-full"
                                                                                autocomplete="end-time"
                                                                                :data-mask="'##:##'"
                                                                                :placeholder="'hh:mm'"
                                                                            />
                                                                            <jet-input-error
                                                                                :message="
                                                                                    form
                                                                                        .errors[
                                                                                        `slots.${slotIndex}.items.${itemIndex}.end_time`
                                                                                    ]
                                                                                "
                                                                                class="mt-2"
                                                                            />
                                                                        </td>
                                                                        <td
                                                                            class="border-t-0 px-6 align-top border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                                                                        >
                                                                            <jet-masked-input
                                                                                :id="`max-bookings-${slotIndex}-${itemIndex}`"
                                                                                v-model="
                                                                                    form
                                                                                        .slots[
                                                                                        slotIndex
                                                                                    ]
                                                                                        .items[
                                                                                        itemIndex
                                                                                    ]
                                                                                        .max_bookings
                                                                                "
                                                                                type="text"
                                                                                class="mt-1 block w-full"
                                                                                autocomplete="max-bookings"
                                                                                :data-mask="'####'"
                                                                                :placeholder="'2'"
                                                                            />
                                                                            <jet-input-error
                                                                                :message="
                                                                                    form
                                                                                        .errors[
                                                                                        `slots.${slotIndex}.items.${itemIndex}.max_bookings`
                                                                                    ]
                                                                                "
                                                                                class="mt-2"
                                                                            />
                                                                        </td>
                                                                        <td
                                                                            class="border-t-0 px-6 align-top border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                                                                        >
                                                                            <jet-masked-input
                                                                                :id="`cost-${slotIndex}-${itemIndex}`"
                                                                                v-model="
                                                                                    form
                                                                                        .slots[
                                                                                        slotIndex
                                                                                    ]
                                                                                        .items[
                                                                                        itemIndex
                                                                                    ]
                                                                                        .cost
                                                                                "
                                                                                type="text"
                                                                                class="mt-1 block w-full"
                                                                                autocomplete="cost"
                                                                                :data-mask="'##.##'"
                                                                                :placeholder="'50.00'"
                                                                            />
                                                                            <jet-input-error
                                                                                :message="
                                                                                    form
                                                                                        .errors[
                                                                                        `slots.${slotIndex}.items.${itemIndex}.cost`
                                                                                    ]
                                                                                "
                                                                                class="mt-2"
                                                                            />
                                                                        </td>
                                                                        <td
                                                                            class="border-t-0 px-6 align-top border-l-0 border-r-0 text-xs whitespace-nowrap p-4"
                                                                        >
                                                                            <button
                                                                                class="text-gray-500 bg-transparent border border-solid border-gray-500 hover:bg-gray-500 hover:text-white active:bg-gray-800 font-bold uppercase text-sm px-6 py-3 rounded outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
                                                                                type="button"
                                                                                @click="
                                                                                    removeSlot(
                                                                                        slotIndex,
                                                                                        itemIndex
                                                                                    )
                                                                                "
                                                                            >
                                                                                <i
                                                                                    class="fas fa-trash-alt"
                                                                                ></i>
                                                                            </button>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                                <tfoot>
                                                                    <tr>
                                                                        <td
                                                                            class="pt-4"
                                                                        >
                                                                            <button
                                                                                class="text-gray-500 bg-transparent border border-solid border-gray-500 hover:bg-gray-500 hover:text-white active:bg-gray-800 font-bold uppercase text-sm px-6 py-3 rounded outline-none focus:outline-none mr-1 mb-1 ease-linear transition-all duration-150"
                                                                                type="button"
                                                                                @click="
                                                                                    addSlot(
                                                                                        slotIndex
                                                                                    )
                                                                                "
                                                                            >
                                                                                Add
                                                                            </button>
                                                                        </td>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <jet-button
                                    :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing"
                                >
                                    Create
                                </jet-button>
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
import JetMaskedInput from '@/Jetstream/MaskedInput'
import JetInputError from '@/Jetstream/InputError'
import JetLabel from '@/Jetstream/Label'
import JetButton from '@/Jetstream/Button'

export default {
    components: {
        AppLayout,
        JetInput,
        JetMaskedInput,
        JetInputError,
        JetLabel,
        JetButton,
    },
    props: ['property_id'],
    data() {
        return {
            form: this.$inertia.form({
                name: '',
                slots: [
                    {
                        day_id: 0,
                        day: 'Sunday',
                        items: [],
                    },
                    {
                        day_id: 1,
                        day: 'Monday',
                        items: [],
                    },
                    {
                        day_id: 2,
                        day: 'Tuesday',
                        items: [],
                    },
                    {
                        day_id: 3,
                        day: 'Wednesday',
                        items: [],
                    },
                    {
                        day_id: 4,
                        day: 'Thursday',
                        items: [],
                    },
                    {
                        day_id: 5,
                        day: 'Friday',
                        items: [],
                    },
                    {
                        day_id: 6,
                        day: 'Saturday',
                        items: [],
                    },
                ],
            }),
            openTab: 0,
        }
    },
    methods: {
        save() {
            this.form.post(route('calendars.store', this.property_id), {
                errorBag: 'save',
                preserveScroll: true,
            })
        },
        toggleTabs(tabNumber) {
            this.openTab = tabNumber
        },
        addSlot(slotIndex) {
            this.form.slots[slotIndex].items.push({
                start_time: '',
                end_time: '',
                max_bookings: 1,
                cost: '',
            })
        },
        removeSlot(slotIndex, itemIndex) {
            return this.form.slots[slotIndex].items.splice(itemIndex, 1)
        },
    },
}
</script>
