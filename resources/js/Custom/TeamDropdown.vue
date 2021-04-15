<template>
    <div class="hidden sm:flex sm:items-center sm:ml-6">
        <div class="ml-3 relative">
            <!-- Teams Dropdown -->
            <jet-dropdown
                v-if="$page.props.jetstream.hasTeamFeatures"
                align="right"
                width="60"
            >
                <template #trigger>
                    <span class="inline-flex rounded-md">
                        <button
                            v-if="belongsToAnyTeam"
                            type="button"
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:bg-gray-50 hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150"
                        >
                            {{ $page.props.user.current_team.name }}

                            <svg
                                class="ml-2 -mr-0.5 h-4 w-4"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </button>
                    </span>
                </template>

                <template #content>
                    <div class="w-60">
                        <!-- Team Management -->
                        <template v-if="$page.props.jetstream.hasTeamFeatures">
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                Manage Team
                            </div>

                            <!-- Team Settings -->
                            <jet-dropdown-link
                                v-if="belongsToAnyTeam"
                                :href="
                                    route(
                                        'teams.show',
                                        $page.props.user.current_team
                                    )
                                "
                            >
                                Team Settings
                            </jet-dropdown-link>

                            <jet-dropdown-link
                                v-if="$page.props.jetstream.canCreateTeams"
                                :href="route('teams.create')"
                            >
                                Create New Team
                            </jet-dropdown-link>

                            <div class="border-t border-gray-100"></div>

                            <!-- Team Switcher -->
                            <div
                                v-if="$page.props.user.owned_teams.length"
                                class="block px-4 py-2 text-xs text-gray-400"
                            >
                                My Teams
                            </div>

                            <template
                                v-for="team in $page.props.user.owned_teams"
                                :key="team.id"
                            >
                                <form @submit.prevent="switchToTeam(team)">
                                    <jet-dropdown-link
                                        v-if="belongsToAnyTeam"
                                        as="button"
                                    >
                                        <div class="flex items-center">
                                            <svg
                                                v-if="
                                                    team.id ==
                                                    $page.props.user
                                                        .current_team_id
                                                "
                                                class="mr-2 h-5 w-5 text-green-400"
                                                fill="none"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                                ></path>
                                            </svg>
                                            <div>
                                                {{ team.name }}
                                                <span
                                                    v-if="!team.is_public"
                                                    class="text-xs bg-gray-200 rounded py-1 px-2"
                                                    >Private</span
                                                >
                                            </div>
                                        </div>
                                    </jet-dropdown-link>
                                </form>
                            </template>
                            <div
                                v-if="$page.props.user.memberships.length"
                                class="block px-4 py-2 text-xs text-gray-400"
                            >
                                My Memberships
                            </div>

                            <template
                                v-for="team in $page.props.user.memberships"
                                :key="team.id"
                            >
                                <form @submit.prevent="switchToTeam(team)">
                                    <jet-dropdown-link
                                        v-if="belongsToAnyTeam"
                                        as="button"
                                    >
                                        <div class="flex items-center">
                                            <svg
                                                v-if="
                                                    team.id ==
                                                    $page.props.user
                                                        .current_team_id
                                                "
                                                class="mr-2 h-5 w-5 text-green-400"
                                                fill="none"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                                ></path>
                                            </svg>
                                            <div>
                                                {{ team.name }}
                                            </div>
                                        </div>
                                    </jet-dropdown-link>
                                </form>
                            </template>
                        </template>
                    </div>
                </template>
            </jet-dropdown>
        </div>

        <!-- Settings Dropdown -->
        <div class="ml-3 relative">
            <jet-dropdown align="right" width="48">
                <template #trigger>
                    <button
                        v-if="$page.props.jetstream.managesProfilePhotos"
                        class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition duration-150 ease-in-out"
                    >
                        <img
                            class="h-8 w-8 rounded-full object-cover"
                            :src="$page.props.user.profile_photo_url"
                            :alt="$page.props.user.name"
                        />
                    </button>

                    <span v-else class="inline-flex rounded-md">
                        <button
                            type="button"
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                        >
                            {{ $page.props.user.name }}

                            <svg
                                class="ml-2 -mr-0.5 h-4 w-4"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </button>
                    </span>
                </template>

                <template #content>
                    <!-- Account Management -->
                    <div class="block px-4 py-2 text-xs text-gray-400">
                        Manage Account
                    </div>

                    <jet-dropdown-link :href="route('profile.show')">
                        Profile
                    </jet-dropdown-link>

                    <jet-dropdown-link
                        v-if="$page.props.jetstream.hasApiFeatures"
                        :href="route('api-tokens.index')"
                    >
                        API Tokens
                    </jet-dropdown-link>

                    <div class="border-t border-gray-100"></div>

                    <!-- Authentication -->
                    <form @submit.prevent="logout">
                        <jet-dropdown-link as="button">
                            Log Out
                        </jet-dropdown-link>
                    </form>
                </template>
            </jet-dropdown>
        </div>
    </div>
</template>

<script>
import JetDropdown from '@/Jetstream/Dropdown'
import JetDropdownLink from '@/Jetstream/DropdownLink'

export default {
    name: 'TeamDropdown.vue',
    components: {
        JetDropdown,
        JetDropdownLink,
    },
}
</script>

<style scoped></style>
