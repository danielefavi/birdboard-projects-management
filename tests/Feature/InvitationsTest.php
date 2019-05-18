<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Project;
use Facades\Tests\Setup\ProjectFactory;



class InvitationsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function non_owners_may_not_invite_users()
    {
        // $this->withoutExceptionHandling();

        $project = ProjectFactory::create();

        $user = factory(\App\User::class)->create();

        $this->actingAs($user)
            ->post($project->path('invitations'))
            ->assertStatus(403);

        $project->invite($user);

        $this->actingAs($user)
            ->post($project->path('invitations'))
            ->assertStatus(403);
    }

    /** @test */
    public function a_project_can_invite_a_user()
    {
        // $this->withoutExceptionHandling();
        $project = ProjectFactory::create();

        $userToInvite = factory(\App\User::class)->create();

        // invite an user
        $this->actingAs($project->owner)
            ->post($project->path('invitations'), [
                'email' => $userToInvite->email
            ])
            ->assertRedirect($project->path());

        $this->assertTrue($project->members->contains($userToInvite));
    }

    /** @test */
    public function the_invited_email_must_exists_in_birdboard()
    {
        // $this->withoutExceptionHandling();

        $project = ProjectFactory::create();

        $this->actingAs($project->owner)->post($project->path('invitations'), [
            'email' => 'email_that_does_not_exist@test.com'
        ])
        ->assertSessionHasErrors([
            'email' => 'The user you are inviting must have a birdboard account.',
        ], null, 'invitations');

    }

    /** @test */
    public function invited_users_may_update_project_details()
    {
        // $this->withoutExceptionHandling();

        // Given: I have a project
        $project = ProjectFactory::create();

        // And the owner of the project invites anothes user
        $newUser = factory(User::class)->create();
        $project->invite($newUser);

        // Then, that user will have permission to add tasks
        $this->signIn($newUser);
        $this->post(action('ProjectTasksController@store', $project), $task = [
            'body' => 'Foo task'
        ]);

        $this->assertDatabaseHas('tasks', $task);
    }

}
