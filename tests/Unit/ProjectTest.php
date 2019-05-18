<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_path()
    {
        $p = factory('App\Project')->create();

        $this->assertEquals('/projects/' . $p->id, $p->path());
    }

    /** @test */
    public function a_project_belongs_to_an_owner()
    {
        $user = factory('App\User')->create();

        $project = factory('App\Project')->create([
            'owner_id' => $user->id
        ]);

        $this->assertInstanceOf(\App\User::class, $project->owner);
        $this->assertEquals($project->owner->id, $user->id);
    }

    /** @test */
    public function it_can_add_a_task()
    {
        $project = factory('App\Project')->create();

        $task = $project->addTask('Test task 123');

        $this->assertCount(1, $project->tasks);
        $this->assertTrue($project->tasks->contains($task));
    }

    /** @test */
    public function it_can_invite_an_user()
    {
        $project = factory('App\Project')->create();

        $project->invite($user = factory(\App\User::class)->create());

        $this->assertTrue( $project->members->contains($user) );
    }
}
