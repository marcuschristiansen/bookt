<template>
    <nav
        class="md:left-0 md:block md:fixed md:top-0 md:bottom-0 md:overflow-y-auto md:flex-row md:flex-nowrap md:overflow-hidden shadow-xl bg-white flex flex-wrap items-center justify-between relative md:w-64 z-10 py-4 px-6"
    >
        <div
            class="md:flex-col md:items-stretch md:min-h-full md:flex-nowrap px-0 flex flex-wrap items-center justify-between w-full mx-auto"
        >
            <!-- Toggler -->
            <button
                class="cursor-pointer text-black opacity-50 md:hidden px-3 py-1 text-xl leading-none bg-transparent rounded border border-solid border-transparent"
                type="button"
                @click="toggleCollapseShow('bg-white m-2 py-3 px-6')"
            >
                <i class="fas fa-bars"></i>
            </button>
            <!-- Brand -->
            <a
                class="md:block text-left md:pb-2 text-blueGray-600 mr-0 inline-block whitespace-nowrap text-sm uppercase font-bold p-4 px-0"
                href="javascript:void(0)"
            >
                Bookt App
            </a>
            <!-- User -->
            <!--            <ul class="md:hidden items-center flex flex-wrap list-none">-->
            <!--                <li class="inline-block relative">-->
            <!--                    &lt;!&ndash;                    <user-dropdown-component></user-dropdown-component>&ndash;&gt;-->
            <!--                    &lt;!&ndash;                    <navbar></navbar>&ndash;&gt;-->
            <!--                </li>-->
            <!--            </ul>-->
            <!-- Collapse -->
            <div
                class="md:flex md:flex-col md:items-stretch md:opacity-100 md:relative md:mt-4 md:shadow-none shadow absolute top-0 left-0 right-0 z-40 overflow-y-auto overflow-x-hidden h-auto items-center flex-1 rounded"
                :class="collapseShow"
            >
                <!-- Collapse header -->
                <div
                    class="md:min-w-full md:hidden block pb-4 mb-4 border-b border-solid border-blueGray-200"
                >
                    <div class="flex flex-wrap">
                        <div class="w-6/12">
                            <a
                                class="md:block text-left md:pb-2 text-blueGray-600 mr-0 inline-block whitespace-nowrap text-sm uppercase font-bold p-4 px-0"
                                href="javascript:void(0)"
                            >
                                Bookt App
                            </a>
                        </div>
                        <div class="w-6/12 flex justify-end">
                            <button
                                type="button"
                                class="cursor-pointer text-black opacity-50 md:hidden px-3 py-1 text-xl leading-none bg-transparent rounded border border-solid border-transparent"
                                @click="toggleCollapseShow('hidden')"
                            >
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Navigation -->
                <ul class="md:flex-col md:min-w-full flex flex-col list-none">
                    <li class="items-center">
                        <jet-nav-link
                            class="text-blueGray-700 hover:text-blueGray-500 text-xs uppercase py-3 font-bold block"
                            :href="route('dashboard')"
                            :active="route().current('dashboard')"
                        >
                            <i class="fas fa-tv opacity-75 mr-2 text-sm"></i>
                            Dashboard
                        </jet-nav-link>
                    </li>
                </ul>
                <!-- Divider -->
                <hr class="my-4 md:min-w-full" />
                <!-- Heading -->
                <h6
                    class="md:min-w-full text-blueGray-500 text-xs uppercase font-bold block pt-1 pb-4 no-underline"
                >
                    My Account
                </h6>
                <ul
                    class="md:flex-col md:min-w-full flex flex-col list-none md:mb-4"
                >
                    <li>
                        <jet-nav-link
                            :href="
                                route(
                                    'user-bookings.index',
                                    $page.props.user.id
                                )
                            "
                            :active="route().current('user-bookings.*')"
                        >
                            <i
                                class="fas fa-calendar-alt opacity-75 mr-2 text-sm"
                            ></i>
                            My Bookings
                        </jet-nav-link>
                    </li>
                    <li>
                        <jet-nav-link
                            :href="
                                route(
                                    'user-properties.index',
                                    $page.props.user.id
                                )
                            "
                            :active="route().current('user-properties.*')"
                        >
                            <i
                                class="fas fa-user-lock opacity-75 mr-2 text-sm"
                            ></i>
                            My Memberships
                        </jet-nav-link>
                    </li>
                </ul>
                <!-- Divider -->
                <hr v-if="isTeamAdmin" class="my-4 md:min-w-full" />
                <!-- Heading -->
                <h6
                    v-if="isTeamAdmin"
                    class="md:min-w-full text-blueGray-500 text-xs uppercase font-bold block pt-1 pb-4 no-underline"
                >
                    Organiser Hub
                </h6>
                <!-- Navigation -->
                <ul
                    v-if="isTeamAdmin"
                    class="md:flex-col md:min-w-full flex flex-col list-none md:mb-4"
                >
                    <li class="inline-flex">
                        <jet-nav-link
                            class="text-blueGray-500 hover:text-blueGray-800 text-xs uppercase py-3 font-bold block"
                            :href="route('properties.index')"
                            :active="route().current('properties.*')"
                        >
                            <i class="fas fa-home opacity-75 mr-2 text-sm"></i>
                            Properties
                        </jet-nav-link>
                    </li>
                    <li class="inline-flex">
                        <jet-nav-link
                            :href="route('bookings.index')"
                            :active="route().current('bookings.*')"
                        >
                            <i
                                class="fas fa-calendar-alt opacity-75 mr-2 text-sm"
                            ></i>
                            Bookings
                        </jet-nav-link>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>
<script>
import JetNavLink from '@/Jetstream/NavLink'
import Navbar from '@/Custom/Navbar'

export default {
    components: {
        Navbar,
        JetNavLink,
    },
    data() {
        return {
            collapseShow: 'hidden',
        }
    },
    computed: {
        isTeamAdmin() {
            return this.$page.props.user.roles.some(
                (role) => role.name === 'team-admin'
            )
        },
    },
    methods: {
        toggleCollapseShow: function (classes) {
            this.collapseShow = classes
        },
    },
}
</script>
