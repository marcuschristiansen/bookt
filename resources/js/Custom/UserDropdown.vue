<template>
    <div>
        <!-- User Management -->
        <a
            v-if="$page.props.jetstream.managesProfilePhotos"
            ref="btnDropdownRef"
            class="text-blueGray-500 block"
            href="#pablo"
            @click="toggleDropdown($event)"
        >
            <div class="items-center flex">
                <span
                    class="w-12 h-12 text-sm text-white bg-blueGray-200 inline-flex items-center justify-center rounded-full"
                >
                    <img
                        :alt="$page.props.user.name"
                        class="w-full rounded-full align-middle border-none shadow-lg"
                        :src="$page.props.user.profile_photo_url"
                    />
                </span>
            </div>
        </a>
        <div
            ref="popoverDropdownRef"
            class="bg-white text-base z-50 float-left py-2 list-none text-left rounded shadow-lg mt-1"
            :class="{
                hidden: !dropdownPopoverShow,
                block: dropdownPopoverShow,
            }"
            style="min-width: 12rem"
        >
            <!-- Account Management -->
            <div class="block px-4 py-2 text-xs text-gray-400">
                Manage Account
            </div>
            <jet-dropdown-link :href="route('profile.show')">
                <i class="fas fa-user opacity-75 mr-2 text-sm"></i>
                Profile
            </jet-dropdown-link>

            <jet-dropdown-link
                v-if="$page.props.jetstream.hasApiFeatures"
                :href="route('api-tokens.index')"
            >
                <i class="fas fa-cloud opacity-75 mr-2 text-sm"></i>
                API Tokens
            </jet-dropdown-link>
            <div class="h-0 my-2 border border-solid border-blueGray-100" />
            <!-- Authentication -->
            <form @submit.prevent="logout">
                <jet-dropdown-link as="button"
                    ><i class="fas fa-sign-out-alt opacity-75 mr-2 text-sm"></i>
                    Log Out
                </jet-dropdown-link>
            </form>
        </div>
    </div>
</template>
<script>
import { createPopper } from '@popperjs/core'
import JetDropdownLink from '@/Jetstream/DropdownLink'
import JetResponsiveNavLink from '@/Jetstream/ResponsiveNavLink'

export default {
    components: {
        JetDropdownLink,
        JetResponsiveNavLink,
    },
    data() {
        return {
            dropdownPopoverShow: false,
            showingNavigationDropdown: false,
        }
    },
    methods: {
        toggleDropdown: function (event) {
            event.preventDefault()
            if (this.dropdownPopoverShow) {
                this.dropdownPopoverShow = false
            } else {
                this.dropdownPopoverShow = true
                createPopper(
                    this.$refs.btnDropdownRef,
                    this.$refs.popoverDropdownRef,
                    {
                        placement: 'bottom-end',
                    }
                )
            }
        },
        logout() {
            this.$inertia.post(route('logout'))
        },
        switchToTeam(team) {
            this.$inertia.put(
                route('current-team.update'),
                {
                    team_id: team.id,
                },
                {
                    preserveState: false,
                }
            )
        },
    },
}
</script>
