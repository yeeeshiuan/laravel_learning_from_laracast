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

        $this->get($project->path())->assertRedirect('login');

        $this->post('/projects', $project->toArray())->assertRedirect('login');
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
    public function a_user_can_update_a_project()
    {

        $project = app(ProjectFactory::class)
                    ->ownedBy($this->signIn())
                    ->withTasks(1)
                    ->create();

        $attributes = ['notes' => 'Changed'];

        $this->patch($project->path(), $attributes)
                ->assertRedirect($project->path());

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
