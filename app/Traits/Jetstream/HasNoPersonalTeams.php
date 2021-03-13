<?php

namespace App\Traits\Jetstream;

use Laravel\Jetstream\Jetstream;

trait HasNoPersonalTeams
{

    /**
     * Determine if the user owns the given team.
     *
     * @param  mixed  $team
     * @return bool
     */
    public function ownsTeam($team)
    {
        return $this->id == optional($team)->user_id;
    }

    /**
     * Determine if the given team is the current team.
     *
     * @param  mixed  $team
     * @return bool
     */
    public function isCurrentTeam($team)
    {
        return optional($team)->id === $this->currentTeam->id;
    }

    /**
     * Determine if the user is assigned to any teams.
     *
     * @return bool
     */
    public function belongsToAnyTeam()
    {
        return (bool) optional($this->teams)->isNotEmpty();
    }

    /**
     * Determine if the user belongs to the given team.
     *
     * @param  mixed  $team
     * @return bool
     */
    public function belongsToTeam($team)
    {
        return $this->teams->contains(function ($t) use ($team) {
                return $t->id === optional($team)->id;
            }) || $this->ownsTeam($team);
    }

    /**
     * Get the current team of the user's context.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currentTeam()
    {
        if (is_null($this->current_team_id) && $this->id) {
            $this->switchTeam($this->allTeams()->first());
        }

        return $this->belongsTo(Jetstream::teamModel(), 'current_team_id');
    }
}
