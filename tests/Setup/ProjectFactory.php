<?php
namespace Tests\Setup;

use App\Project;
use App\Task;
use App\User;


class ProjectFactory
{
    protected $tasksCount = 0;
    protected $user = null;

    /**
     * Tasks related to the project to create.
     *
     * @param numeric $count
     * @return ProjectFactory
     */
    public function withTasks($count)
    {
        $this->tasksCount = $count;

        return $this;
    }

    /**
     * Associate a user to the project is going to be created.
     *
     * @param App\User $user
     * @return ProjectFactory
     */
    public function ownedBy($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Generate a project.
     *
     * @return App\Project
     */
    public function create()
    {
        $project = factory(Project::class)->create([
            'owner_id' => $this->user ?? factory(User::class),
        ]);

        factory(Task::class, $this->tasksCount)->create([
            'project_id' => $project->id,
        ]);

        return $project;
    }
}
