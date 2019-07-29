<?php

namespace Tests\Feature\Job;

use App\Job;
use App\User;
use App\Company;
use Tests\TestCase;
use App\Achievement;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BrowseTest extends TestCase
{
    use RefreshDatabase;

    private function browseModels($params = null)
    {
        $response = $this->json('GET', '/api/jobs' . $params);

        return $response;
    }

    /** @test */
    public function testUserCanBrowseJobs()
    {
        $this->withoutExceptionHandling();

        factory(Job::class, 5)->create();

        $response = $this->browseModels();

        $response->assertStatus(200)->assertJsonCount(5);
    }

    /** @test */
    function testSortJobsByName()
    {
        $this->withoutExceptionHandling();

        $jobOne = factory(Job::class)->create([
            'title' => 'Alpha'
        ]);
        $jobTwo = factory(Job::class)->create([
            'title' => 'Zulu'
        ]);
        $jobThree = factory(Job::class)->create([
            'title' => 'Hotel'
        ]);

        $response = $this->browseModels('?sort=title');

        $response->assertStatus(200)->assertJsonCount(3);

        $returnedData = collect($response->json());
        $this->assertEquals($jobOne->title, $returnedData->first()['title']);
        $this->assertEquals($jobTwo->title, $returnedData->last()['title']);
    }

    /** @test */
    public function testReturnsJobsWithTheirAchievementsRelationship()
    {
        $this->withoutExceptionHandling();

        factory(Job::class, 5)
            ->create()
            ->each(function ($job) {
                $job
                    ->achievements()
                    ->save(factory(Achievement::class)->create());
            });

        $response = $this->browseModels('?include=achievements');

        $response->assertStatus(200)->assertJsonCount(5);

        collect($response->json())->each(function ($job) {
            $this->assertNotEmpty(
                $job['achievements'],
                "An job is missing the relationship"
            );
        });
    }

    /** @test */
    public function testReturnsJobsWithTheCompanyTheyBelongsTo()
    {
        $this->withoutExceptionHandling();

        factory(Job::class, 5)
            ->create()
            ->each(function ($job) {
                $job->company()->associate(factory(Company::class)->create());
                $job->save();
            });

        $response = $this->browseModels('?include=company');

        $response->assertStatus(200)->assertJsonCount(5);

        collect($response->json())->each(function ($job) {
            $this->assertNotEmpty(
                $job['company'],
                "An job is missing the relationship"
            );
        });
    }
}
