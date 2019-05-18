<?php

namespace Tests\Feature;

use Tests\TestCase;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Project;
use App\Task;

class ProjectTasksTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_project_can_have_tasks()
    {
        // $this->withoutExceptionHandling();

        // First way:
        // $this->signIn();
        //
        // $project = auth()->user()->projects()->create(
        //     factory(Project::class)->raw()
        // );

        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
            ->post($project->path('tasks'), ['body' => 'Lorem Ipsum Test Task']);

        $this->get($project->path())
            ->assertSee('Lorem Ipsum Test Task');
    }



    /** @test */
    public function only_the_owner_of_a_project_may_add_task()
    {
        $this->signIn();

        // creating a project not related to the signed user
        $project = factory('App\Project')->create();

        $this->post($project->path('tasks'), ['body' => 'test 1234'])
            ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', ['body' => 'test 1234']);
    }



    /** @test */
    public function only_the_owner_of_a_project_may_update_a_task()
    {
        $this->signIn();

        $project = ProjectFactory::withTasks(1)->create();

        $this->patch($project->tasks[0]->path(), ['body' => 'changed'])
            ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', ['body' => 'changed']);
    }



    /** @test */
    public function a_task_can_be_updated()
    {
        // $this->withoutExceptionHandling();

        // Normal way to do
        // $project = app(ProjectFactory::class)
        //     // ->ownedBy($this->signIn()) // --> 2 ways to do it: or sign in or acting as in the patch request
        //     ->withTasks(1)
        //     ->create();

        // NOTE: after adding Facades a the beginnig of the namespace of Tests\Setup\ProjectFactory
        // I can call statically the methods:
        $project = ProjectFactory::withTasks(1)->create();

        $this->actingAs($project->owner)
            ->patch($project->tasks[0]->path(), [
                'body' => 'The task body has been changed',
            ]);

        $this->assertDatabaseHas('tasks', [
            'body' => 'The task body has been changed',
        ]);
    }


    /** @test */
    public function a_task_can_be_completed()
    {
        $project = ProjectFactory::withTasks(1)->create();

        $this->actingAs($project->owner)
            ->patch($project->tasks[0]->path(), [
                'body' => 'The task body has been changed',
                'completed' => true,
            ]);

        $this->assertDatabaseHas('tasks', [
            'body' => 'The task body has been changed',
            'completed' => true,
        ]);
    }


    /** @test */
    public function a_task_can_be_marked_as_incompleted()
    {
        $project = ProjectFactory::withTasks(1)->create();

        $this->actingAs($project->owner)
            ->patch($project->tasks[0]->path(), [
                'body' => 'The task body has been changed',
                'completed' => true,
            ]);

        $this->actingAs($project->owner)
            ->patch($project->tasks[0]->path(), [
                'body' => 'The task body has been changed again',
                'completed' => false,
            ]);

        $this->assertDatabaseHas('tasks', [
            'body' => 'The task body has been changed again',
            'completed' => false,
        ]);
    }


    /** @test */
    public function a_task_requires_a_body()
    {
        // $this->withoutExceptionHandling();

        // First way
        // $this->signIn();
        //
        // $project = auth()->user()->projects()->create(
        //     factory(Project::class)->raw()
        // );

        $project = ProjectFactory::create();

        $attr = factory(Task::class)->raw(['body' => '']);

        $this->actingAs($project->owner)
            ->post($project->path('tasks'), $attr)
            ->assertSessionHasErrors('body');
    }
}
