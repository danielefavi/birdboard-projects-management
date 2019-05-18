<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;
use App\Task;



class ProjectTasksController extends Controller
{

    /**
     *
     *
     * @param Parameter $parameter
     * @return string
     */
    public function store(Project $project)
    {
        $this->authorize('update', $project);

        request()->validate([
            'body' => 'required'
        ]);

        $project->addTask(request('body'));

        return redirect($project->path());
    }



    /**
     * Update the given project.
     *
     * @param Project $project
     * @param Task $task
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Project $project, Task $task)
    {
        $this->authorize('update', $task->project);

        $attr = request()->validate([
            'body' => 'required'
        ]);

        $task->update($attr);

        request('completed') ? $task->complete() : $task->incomplete();

        return redirect($project->path());
    }

}
