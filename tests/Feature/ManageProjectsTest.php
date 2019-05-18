<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Facades\Tests\Setup\ProjectFactory;
use App\Project;

class ManageProjectsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function a_user_can_create_a_project()
    {
        // $this->withoutExceptionHandling();

        $this->signIn();

        $this->get('/projects/create')->assertStatus(200);

        $attr = factory(Project::class)->raw();

        $this->followingRedirects()->post('/projects', $attr)
            ->assertSee($attr['title'])
            ->assertSee($attr['description'])
            ->assertSee($attr['notes']);
    }

    /** @test */
    public function a_user_can_see_all_projects_they_have_been_invited_to_on_their_dashboard()
    {
        // $this->withoutExceptionHandling();

        // given we are signed in
        $user = $this->signIn();

        // and we have been invited to a project that has was not created by us
        // $project = ProjectFactory::create();
        // $project->invite($user);
        $project = tap(ProjectFactory::create())->invite($user);


        // when I visit my dashboard I should see the project
        $this->get('/projects')->assertSee($project->title);
    }

    /** @test */
    public function unauthorized_cannot_delete_projects()
    {
        // $this->withoutExceptionHandling();

        $project = ProjectFactory::create();

        $this->delete($project->path())
            ->assertRedirect('/login');

        $user = $this->signIn();

        $this->delete($project->path())->assertStatus(403);

        $project->invite($user);

        $this->actingAs($user)
            ->delete($project->path())
            ->assertStatus(403);
    }

    /** @test */
    public function a_user_ca_delete_a_project()
    {
        $this->withoutExceptionHandling();

        $p = ProjectFactory::create();

        $this->actingAs($p->owner)
            ->delete($p->path())
            ->assertRedirect('/projects');

        $this->assertDatabaseMissing('projects', $p->only('id'));

        // other ways to do the same thing
        $this->assertfalse($p->exists());
        $this->assertNull($p->fresh());
    }


    /** @test */
    public function a_user_can_update_a_project()
    {
        // $this->withoutExceptionHandling();

        // First way
        // $this->signIn();
        //
        // $p = factory('App\Project')->create(['owner_id' => auth()->id()]);

        $p = ProjectFactory::create();

        $attr = [
            'title' => 'Changed!',
            'description' => 'Description changed!',
            'notes' => 'Changed as well!',
        ];

        $this->actingAs($p->owner)
            ->patch($p->path(), $attr)
            ->assertRedirect($p->path());

        $this->get($p->path('edit'))->assertOk();

        $this->assertDatabaseHas('projects', $attr);
    }

    /** @test */
    public function a_user_can_view_their_project()
    {
        // $this->withoutExceptionHandling();

        // First Way
        // $this->signIn();
        // $p = factory('App\Project')->create(['owner_id' => auth()->id()]);

        $p = ProjectFactory::create();

        $this->actingAs($p->owner)
            ->get($p->path())
            ->assertSee($p->title)
            ->assertSee($p->description);
    }

    /** @test */
    public function an_auth_user_cannot_view_project_of_others()
    {
        // $this->withoutExceptionHandling();

        $this->signIn();

        $p = factory('App\Project')->create();

        $this->get($p->path())->assertStatus(403);
    }

    /** @test */
    public function an_auth_user_cannot_update_project_of_others()
    {
        // $this->withoutExceptionHandling();

        $this->signIn();

        $p = factory('App\Project')->create();

        $this->patch($p->path(), [
            'notes' => 'Changed!'
        ])->assertStatus(403);
    }

    /** @test */
    public function a_project_requires_a_title()
    {
        $this->signIn();

        $attr = factory('App\Project')->raw([
            'title' => ''
        ]);

        $this->post('/projects', $attr)->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_project_requires_a_description()
    {
        // $this->withoutExceptionHandling();

        $this->signIn();

        $attr = factory('App\Project')->raw([
            'description' => ''
        ]);

        $this->post('/projects', $attr)->assertSessionHasErrors('description');
    }

    /** @test */
    public function guests_cannot_manage_projects()
    {
        $p = factory('App\Project')->create();

        $this->get('/projects')->assertRedirect('login');
        $this->get('/projects/create')->assertRedirect('login');
        $this->get($p->path('edit'))->assertRedirect('login');
        $this->post('/projects', $p->toArray())->assertRedirect('login');
        $this->get($p->path())->assertRedirect('login');
    }

    /** @test */
    public function an_user_can_update_a_project_general_notes()
    {
        // $this->withoutExceptionHandling();

        $p = ProjectFactory::create();

        $attr = [
            'notes' => 'Changed as well!',
        ];

        $this->actingAs($p->owner)->patch($p->path(), $attr);

        $this->assertDatabaseHas('projects', $attr);
    }

}
