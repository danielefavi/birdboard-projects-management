<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProjectInvitationRequest;
use App\User;
use App\Project;

class ProjectInvitationsController extends Controller
{

    /**
     * Invite an user to a project.
     *
     * @param Project $project
     * @param ProjectInvitationRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(Project $project, ProjectInvitationRequest $request)
    {
        $user = User::whereEmail(request('email'))->first();

        $project->invite($user);

        return redirect($project->path());
    }
}
