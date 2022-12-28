<?php

namespace App\Http\Controllers;

use App\Enums\TeamRoleEnum;
use App\Models\Team;
use App\Models\TeamRole;
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
    public function index(Request $request)
    {
        return view('teams.index', [
            'user' => $request->user(),
        ]);
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


        return Redirect::route('teams.index');
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

        return Redirect::route('teams.index');
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

        return Redirect::route('teams.index');
    }


    /**
     * Join a team
     *
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function join(Team $team)
    {
        $this->authorize('join', $team);

        $user = Auth::user();
        $user->teams()->attach($team);

        $teamRole = new TeamRole();
        $teamRole->team_id = $team->id;
        $teamRole->user_id = $user->id;
        $teamRole->role = TeamRoleEnum::MEMBER;
        $teamRole->save();

        return Redirect::route('teams.index');
    }

    /**
     * Unjoin a team
     *
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function unJoin(Team $team)
    {
        $this->authorize('unjoin', $team);

        $user = Auth::user();
        $user->teams()->detach($team->id);
        TeamRole::where(['team_id' => $team->id, 'user_id' => $user->id])->delete();

        return Redirect::route('teams.index');
    }

     /**
     * Give or role to a team member
     *
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function role(Team $team, Request $request)
    {
        $this->authorize('role', $team);

        $givenUserId = $request->get("user_id");
        $role = $request->get("role");

        $teamRole = new TeamRole();
        $teamRole->team_id = $team->id;
        $teamRole->user_id = $givenUserId;
        $teamRole->role = $role;
        $teamRole->save();

        return Redirect::route('teams.index');
    }
}
