<?php

namespace App\Policies;

use App\Enums\TeamRoleEnum;
use App\Models\Team;
use App\Models\TeamRole;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Log;

class TeamPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Team $team)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Team $team)
    {
        if (!$user->teams->contains($team)) {
            // User is not a member of the team
            return false;
        }

        $teamRole = TeamRole::where(['team_id' => $team->id, 'user_id' => $user->id])->firstOrFail();
        if ($teamRole->role !== TeamRoleEnum::ADMIN->value) {
            // User is not a admin
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Team $team)
    {
        if (!$user->teams->contains($team)) {
            // User is not a member of the team
            return false;
        }

        $teamRole = TeamRole::where(['team_id' => $team->id, 'user_id' => $user->id])->firstOrFail();
        if ($teamRole->role !== TeamRoleEnum::ADMIN->value) {
            // User is not a admin
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Team $team)
    {
        return $this->delete($user, $team);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Team $team)
    {
        return $this->delete($user, $team);
    }

    /**
     * Determine whether the user can unjoin a team
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function unjoin(User $user, Team $team)
    {
        // Check if the user is an admin. Admin's can't leave the team, they need to delete the team first. Else, no-one would have the right
        // to delete or administrate the team.
        if (TeamRole::where(['team_id' => $team->id, 'user_id' => $user->id, 'role' => TeamRoleEnum::ADMIN->value])->count() > 0) {
            Log::error("An admin can't unjoin a team because we need someone to be able to delete/manage the team. Admin can empty a team and than delete the team or switch roles.");
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can give a roll to a team-member
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function role(User $user, Team $team)
    {
        // Check if the user is an admin. Admin's can add roles to team-members
        if (TeamRole::where(['team_id' => $team->id, 'user_id' => $user->id, 'role' => TeamRoleEnum::ADMIN->value])->count() == 0) {
            Log::error("You need admin right to give roles to Team member.");
            return false;
        }

        return true;
    }
}
