<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InvitationsTest extends TestCase
{
    
    use RefreshDatabase;

    /** @test **/
    function a_project_can_invite_a_user()
    {

        // Given I have a project

        $project = ProjectFactory::create();

        // And the owner of the project invites another user

        $project->invite($newUser = factory(User::class)->create());

        // Then, that new user will have permision to add tasks

        $this->signIn($newUser);

        $this->post(action('ProjectTasksController@store', $project), 
                    $task = ['body' => 'Foo task']);

        $this->assertDatabaseHas('tasks', $task);

    }
}
