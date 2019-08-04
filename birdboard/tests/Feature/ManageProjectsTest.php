<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tests\Setup\ProjectFactory;
use App\Project;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManageProjectsTest extends TestCase
{

    use WithFaker, RefreshDatabase;

    /** @test **/
    public function guests_cannot_manage_projects()
    {

        $project = factory('App\Project')->create();


        $this->get('/projects')->assertRedirect('login');

        $this->get('/projects/create')->assertRedirect('login');

        $this->get($project->path() . '/edit')->assertRedirect('login');

        $this->get($project->path())->assertRedirect('login');

        $this->post('/projects', $project->toArray())->assertRedirect('login');
    }

    /** @test **/
    public function unauthorize_users_can_not_delete_a_project()
    {

        $project = app(ProjectFactory::class)
                    ->create();

        $this->delete($project->path())
                ->assertRedirect('/login');

        $this->signIn();

        $this->delete($project->path())
                ->assertStatus(403);

    }

    /** @test **/
    public function a_user_can_create_a_project()
    {

        $this->signIn();

        $this->get('/projects/create')->assertStatus(200);

        $attributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->sentence,
            'notes' => 'General notes here.'
        ];

        $response = $this->post('/projects', $attributes);

        $project = Project::where($attributes)->first();

        $response->assertRedirect($project->path());

        $this->assertDatabaseHas('projects', $attributes);

        $this->get($project->path())
                ->assertSee($attributes['title'])
                ->assertSee($attributes['description'])
                ->assertSee($attributes['notes']);

    }

    /** @test **/
    public function a_user_can_see_all_projects_they_have_been_invited_to_on_their_dashboard()
    {

        $this->withoutExceptionHandling();

        // given we're signed in

        $user = $this->signIn();

        // and we've been invited to a project that was not by created by us

        $project = app(ProjectFactory::class)
                    ->create();

        $project->invite($user);

        // when I visit my dashboard
        // I should see that project.
        $this->get('/projects')->assertSee($project->title);
    }

    /** @test **/
    public function a_user_can_delete_a_project()
    {

        $this->withoutExceptionHandling();

        $project = app(ProjectFactory::class)
                    ->ownedBy($this->signIn())
                    ->create();

        $this->delete($project->path())
                ->assertRedirect('/projects');

        $this->assertDatabaseMissing('projects', $project->only('id'));

    }

    /** @test **/
    public function a_user_can_update_a_project()
    {

        $this->withoutExceptionHandling();

        $project = app(ProjectFactory::class)
                    ->ownedBy($this->signIn())
                    ->withTasks(1)
                    ->create();

        $attributes = ['title' => 'Changed', 
                       'description' => 'Changed', 
                       'notes' => 'Changed'];

        $this->patch($project->path(), $attributes)
                ->assertRedirect($project->path());

        $this->get($project->path() . '/edit')->assertStatus(200);

        $this->assertDatabaseHas('projects', $attributes);

    }

    /** @test **/
    public function a_user_can_update_a_projects_general_notes()
    {
        $project = app(ProjectFactory::class)
                    ->ownedBy($this->signIn())
                    ->withTasks(1)
                    ->create();

        $attributes = ['notes' => 'Changed'];

        $this->patch($project->path(), $attributes)
                ->assertRedirect($project->path());

        $this->get($project->path())->assertStatus(200);

        $this->assertDatabaseHas('projects', $attributes);
    }

    /** @test **/
    public function a_user_can_view_their_project()
    {

        $project = app(ProjectFactory::class)
                    ->ownedBy($this->signIn())
                    ->create();

        $this->get($project->path())
                ->assertSee($project->title)
                ->assertSee($project->description);

    }

    /** @test **/
    public function an_authenticated_user_cannot_view_the_projects_of_others()
    {

        $this->signIn();

        $project = factory('App\Project')->create();

        $this->get($project->path())->assertStatus(403);

    }

    /** @test **/
    public function an_authenticated_user_cannot_update_the_projects_of_others()
    {

        $this->signIn();

        $project = factory('App\Project')->create();

        $this->patch($project->path(), [])->assertStatus(403);

    }

    /** @test **/
    public function a_project_requires_a_title()
    {
        $this->signIn();

        $attributes = factory('App\Project')->raw(['title' => '']);

        $this->post('/projects', $attributes)->assertSessionHasErrors('title');
    }

    /** @test **/
    public function a_project_requires_a_description()
    {
        $this->signIn();

        $attributes = factory('App\Project')->raw(['description' => '']);

        $this->post('/projects', $attributes)->assertSessionHasErrors('description');
    }

}
