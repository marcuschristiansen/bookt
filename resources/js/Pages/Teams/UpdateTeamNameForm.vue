<template>
    <jet-form-section @submitted="updateTeamName">
        <template #title> Team Name </template>

        <template #description>
            The team's name and owner information.
        </template>

        <template #form>
            <!-- Team Owner Information -->
            <div class="col-span-6">
                <jet-label value="Team Owner" />

                <div class="flex items-center mt-2">
                    <img
                        class="w-12 h-12 rounded-full object-cover"
                        :src="team.owner.profile_photo_url"
                        :alt="team.owner.name"
                    />

                    <div class="ml-4 leading-tight">
                        <div>{{ team.owner.name }}</div>
                        <div class="text-gray-700 text-sm">
                            {{ team.owner.email }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Team Name -->
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="name" value="Team Name" />

                <jet-input
                    id="name"
                    v-model="form.name"
                    type="text"
                    class="mt-1 block w-full"
                    :disabled="!permissions.canUpdateTeam"
                />

                <jet-input-error :message="form.errors.name" class="mt-2" />
            </div>

            <!-- Private/Public -->
            <div class="col-span-6 sm:col-span-4">
                <label class="flex items-center">
                    <jet-checkbox
                        v-model:checked="form.is_public"
                        :value="form.is_public"
                    />
                    <span class="ml-2 text-sm text-gray-600">Is public?</span>
                </label>
            </div>
        </template>

        <template v-if="permissions.canUpdateTeam" #actions>
            <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                Saved.
            </jet-action-message>

            <jet-button
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
            >
                Save
            </jet-button>
        </template>
    </jet-form-section>
</template>

<script>
import JetActionMessage from '@/Jetstream/ActionMessage'
import JetButton from '@/Jetstream/Button'
import JetFormSection from '@/Jetstream/FormSection'
import JetInput from '@/Jetstream/Input'
import JetCheckbox from '@/Jetstream/Checkbox'
import JetInputError from '@/Jetstream/InputError'
import JetLabel from '@/Jetstream/Label'

export default {
    components: {
        JetActionMessage,
        JetButton,
        JetFormSection,
        JetInput,
        JetCheckbox,
        JetInputError,
        JetLabel,
    },

    props: ['team', 'permissions'],

    data() {
        return {
            form: this.$inertia.form({
                name: this.team.name,
                is_public: this.team.is_public,
            }),
        }
    },

    methods: {
        updateTeamName() {
            this.form.put(route('teams.update', this.team), {
                errorBag: 'updateTeamName',
                preserveScroll: true,
            })
        },
    },
}
</script>
