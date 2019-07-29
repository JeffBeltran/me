<?php

namespace Tests\Feature\Job;

use App\Job;
use App\User;
use App\Company;
use Tests\TestCase;
use App\Achievement;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadTest extends TestCase
{
    use RefreshDatabase;

    private function readModel($id, $params = null)
    {
        return $this->json('GET', "/api/jobs/$id" . $params);
    }

    /** @test */
    public function testReturnsJobDetailsForGivenID()
    {
        $this->withoutExceptionHandling();

        $job = factory(Job::class)->create();

        $response = $this->readModel($job->id);

        $response->assertStatus(200)->assertExactJson([
            'id' => $job->id,
            'company_id' => $job->company_id,
            'title' => $job->title,
            'blurb' => $job->blurb,
            'state' => $job->state,
            'city' => $job->city,
            'start' => $job->start->toDateTimeString(),
            'end' => $job->end ? $job->end->toDateTimeString() : null,
            'created_at' => $job->created_at->toDateTimeString(),
            'updated_at' => $job->updated_at->toDateTimeString()
        ]);
    }

    /** @test */
    public function testReturnsJobDetailsWithNullables()
    {
        $this->withoutExceptionHandling();

        $job = factory(Job::class)->create([
            'blurb' => null,
            'state' => null,
            'city' => null,
            'end' => null
        ]);

        $response = $this->readModel($job->id);

        $response->assertStatus(200)->assertExactJson([
            'id' => $job->id,
            'company_id' => $job->company_id,
            'title' => $job->title,
            'blurb' => $job->blurb,
            'state' => $job->state,
            'city' => $job->city,
            'start' => $job->start->toDateTimeString(),
            'end' => $job->end,
            'created_at' => $job->created_at->toDateTimeString(),
            'updated_at' => $job->updated_at->toDateTimeString()
        ]);
    }

    /** @test */
    public function testReturnsJobDetailsWithTheirAchievements()
    {
        $this->withoutExceptionHandling();

        $job = factory(Job::class)->create();
        factory(Achievement::class, 3)->create([
            'job_id' => $job->id
        ]);

        $response = $this->readModel($job->id, '?include=achievements');

        $response->assertStatus(200)->assertJsonCount(3, 'achievements');
    }

    /** @test */
    public function testReturnsJobDetailsWithTheCompany()
    {
        $this->withoutExceptionHandling();

        $company = factory(Company::class)->create();
        $job = factory(Job::class)->create([
            'company_id' => $company->id
        ]);

        $response = $this->readModel($job->id, '?include=company');

        $response->assertStatus(200)->assertJson([
            'company' => $company->toArray()
        ]);
    }

    /** @test */
    public function testReturns404ErrorIfNoModelExists()
    {
        factory(Job::class)->create();

        $response = $this->readModel(22);

        $response->assertStatus(404);
        $this->assertEquals(1, Job::count());
    }
}
