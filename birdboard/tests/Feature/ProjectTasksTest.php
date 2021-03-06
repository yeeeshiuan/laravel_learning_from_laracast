<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Project;
use Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTasksTest extends TestCase
{

    use RefreshDatabase;

    /** @test **/
    public function only_the_owner_of_a_project_may_add_tasks()
    {

        $this->signIn();

        $project = factory('App\Project')->create();

        $this->post($project->path() . '/tasks', ['body' => 'Test task'])
                ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', ['body' => 'Test task']);


    }

    /** @test **/
    public function only_the_owner_of_a_project_may_update_a_task()
    {

        $this->signIn();

        $project = app(ProjectFactory::class)
                    ->withTasks(1)
                    ->create();

        $this->patch($project->tasks[0]->path(), ['body' => 'changed'])
                ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', ['body' => 'changed']);


    }

    /** @test **/
    public function a_project_can_have_tasks()
    {

        $project = app(ProjectFactory::class)
                    ->ownedBy($this->signIn())
                    ->withTasks(1)
                    ->create();

        $this->post($project->path() . '/tasks', ['body' => 'Test task']);

        $this->get($project->path())
                ->assertSee('Test task');

    }

    /** @test **/
    public function a_task_can_be_updated()
    {

        $project = app(ProjectFactory::class)
                    ->ownedBy($this->signIn())
                    ->withTasks(1)
                    ->create();

        $this->patch($project->tasks[0]->path(), [

            'body' => 'changed'

        ]);

        $this->assertDatabaseHas('tasks', [

            'body' => 'changed'

        ]);


    }

    /** @test **/
    public function a_task_can_be_completed()
    {

        $project = app(ProjectFactory::class)
                    ->ownedBy($this->signIn())
                    ->withTasks(1)
                    ->create();

        $this->patch($project->tasks[0]->path(), [

            'body' => 'changed',

            'completed' => true

        ]);

        $this->assertDatabaseHas('tasks', [

            'body' => 'changed',

            'completed' => true

        ]);


    }

    /** @test **/
    public function a_task_can_be_marked_as_incompleted()
    {

        $project = app(ProjectFactory::class)
                    ->ownedBy($this->signIn())
                    ->withTasks(1)
                    ->create();

        $this->patch($project->tasks[0]->path(), [

            'body' => 'changed',

            'completed' => true

        ]);

        $this->patch($project->tasks[0]->path(), [

            'body' => 'changed',

            'completed' => false

        ]);

        $this->assertDatabaseHas('tasks', [

            'body' => 'changed',

            'completed' => false

        ]);


    }

    /** @test **/
    public function a_task_requires_a_body()
    {

        $project = app(ProjectFactory::class)
                    ->ownedBy($this->signIn())
                    ->create();

        $attributes = factory('App\Task')->raw(['body' => '']);

        $this->post($project->path() . '/tasks', $attributes)->assertSessionHasErrors('body');

    }
}
