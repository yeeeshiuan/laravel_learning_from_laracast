<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Task;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TriggerActivityTest extends TestCase
{
    
    use RefreshDatabase;

    /** @test **/
    function creating_a_project()
    {

        $project = ProjectFactory::create();

        $this->assertCount(1, $project->activity);

        $this->assertEquals('created', $project->activity[0]->description);

    }

    /** @test **/
    function updating_a_project()
    {

        $project = ProjectFactory::create();

        $project->update(['title' => 'Changed']);

        $this->assertCount(2, $project->activity);

        $this->assertEquals('created', $project->activity[0]->description);

        $this->assertEquals('updated', $project->activity[1]->description);

    }

    /** @test **/
    function creating_a_new_task()
    {

        $project = ProjectFactory::create();

        $project->addTask('Some task');

        $this->assertCount(2, $project->activity);

        tap($project->activity->last(), function ($activity) {

            $this->assertEquals('created_task', $activity->description);

            $this->assertInstanceOf(Task::class, $activity->subject);

            $this->assertEquals('Some task', $activity->subject->body);

        });

        $this->assertEquals('created', $project->activity[0]->description);

        $this->assertEquals('created_task', $project->activity[1]->description);

    }

    /** @test **/
    function completing_a_task()
    {
        $project = ProjectFactory::withTasks(1)->create();

        $this->actingAs($project->owner)
                ->patch($project->tasks[0]->path(), [

                    'body' => 'foobar', 

                    'completed' => true
                ]);

        $this->assertCount(3, $project->activity);

        tap($project->activity->last(), function ($activity) {

            $this->assertEquals('completed_task', $activity->description);

            $this->assertInstanceOf(Task::class, $activity->subject);

            $this->assertEquals('foobar', $activity->subject->body);

        });

        $this->assertEquals('created', $project->activity[0]->description);

        $this->assertEquals('created_task', $project->activity[1]->description);

        $this->assertEquals('completed_task', $project->activity[2]->description);
    }

    /** @test **/
    function incompleting_a_task()
    {
        $project = ProjectFactory::withTasks(1)->create();

        $this->actingAs($project->owner)
                ->patch($project->tasks[0]->path(), [

                    'body' => 'foobar', 

                    'completed' => true
                ]);

        $this->assertCount(3, $project->activity);

        $this->actingAs($project->owner)
                ->patch($project->tasks[0]->path(), [

                    'body' => 'foobar', 

                    'completed' => false
                ]);

        $project = $project->fresh();

        $this->assertCount(4, $project->activity);

        $this->assertEquals('created', $project->activity[0]->description);

        $this->assertEquals('created_task', $project->activity[1]->description);

        $this->assertEquals('completed_task', $project->activity[2]->description);

        $this->assertEquals('incompleted_task', $project->activity[3]->description);
    }

    /** @test **/
    function deleting_a_task()
    {

        $project = ProjectFactory::withTasks(1)->create();

        $project->tasks[0]->delete();

        $this->assertCount(3, $project->activity);


    }

}
