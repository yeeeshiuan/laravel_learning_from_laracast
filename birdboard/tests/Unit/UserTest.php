<?php

namespace Tests\Unit;

use Tests\TestCase;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Collection;


class UserTest extends TestCase
{
	use RefreshDatabase;
	
    /** @test **/
    public function a_user_has_projects()
    {

    	$user = factory('App\User')->create();

    	$this->assertInstanceOf(Collection::class, $user->projects);

    }

    /** @test **/
    public function a_user_has_accessible_projects()
    {

    	$john = $this->signIn();

    	ProjectFactory::ownedBy($john)->create();

    	$this->assertCount(1, $john->accessibleProjects());

    	$sally = factory(\App\User::class)->create();

    	$nick = factory(\App\User::class)->create();

    	ProjectFactory::ownedBy($sally)->create()->invite($nick);

    	$this->assertCount(1, $john->accessibleProjects());

    	ProjectFactory::ownedBy($sally)->create()->invite($john);

    	$this->assertCount(2, $john->accessibleProjects());

    }
}
