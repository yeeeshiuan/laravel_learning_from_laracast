<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Project;
use App\User;
use Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ActivityTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    function it_has_a_user()
    {

    	$user = $this->signIn();

    	$project = app(ProjectFactory::class)->ownedBy($user)->create();

    	$this->assertEquals($user->id, $project->activity->first()->user->id);

    }
}
