<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Project;



class ProjectPolicy
{
    use HandlesAuthorization;


    /**
     * Check if the authenticated user is the owner.
     *
     * @param User $user
     * @param Project $project
     * @return boolean
     */
    public function manage(User $user, Project $project)
    {
        return $user->is($project->owner);
    }



    /**
     * Determine if the user can update the project.
     *
     * @param User $user
     * @param Project $project
     * @return boolean
     */
    public function update(User $user, Project $project)
    {
        return $user->is($project->owner) || $project->members->contains($user);
    }
}
