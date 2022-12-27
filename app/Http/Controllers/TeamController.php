<?php

namespace App\Http\Controllers;

use App\Enums\TeamRoleEnum;
use App\Models\Team;
use App\Models\TeamRole;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $team = Team::create(['name' => $request->get('name')]);
        $user->teams()->attach($team);


        $teamRole = new TeamRole();
        $teamRole->team_id = $team->id;
        $teamRole->user_id = $user->id;
        $teamRole->role = TeamRoleEnum::ADMIN;
        $teamRole->save();


        return Redirect::route('team.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function show(Team $team)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function edit(Team $team)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Team $team)
    {
        $this->authorize('update', $team);

        $team->name = $request->get("name");
        $team->save();

        return Redirect::route('team.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function destroy(Team $team)
    {
        $this->authorize('delete', $team);

        $team->delete();

        return Redirect::route('team.index');
    }


    /**
     * Join a team
     *
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function join(Team $team)
    {
        $user = Auth::user();
        $user->teams()->attach($team);

        $teamRole = new TeamRole();
        $teamRole->team_id = $team->id;
        $teamRole->user_id = $user->id;
        $teamRole->role = TeamRoleEnum::MEMBER;
        $teamRole->save();



        return Redirect::route('team.index');
    }

    /**
     * Unjoin a team
     *
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function unJoin(Team $team)
    {
        $user = Auth::user();

        // Check if the user is an admin. Admin's can't leave the team, they need to delete the team first. Else, no-one would have the right
        // to delete or administrate the team.

        if (TeamRole::where(['team_id' => $team->id, 'user_id' => $user->id, 'role' => TeamRoleEnum::ADMIN->value])->count() > 0) {
            throw new Exception("An admin can't unjoin a team because we need someone to be able to delete/manage the team. Admin can empty a team and than delete the team or switch roles.");
        }

        $user->teams()->detach($team->id);

        TeamRole::where(['team_id' => $team->id, 'user_id' => $user->id])->delete();


        return Redirect::route('team.index');
    }
}
