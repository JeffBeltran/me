<?php

namespace Tests\Feature\Achievement;

use App\Job;
use Tests\TestCase;
use App\Achievement;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadTest extends TestCase
{
    use RefreshDatabase;

    private function readModel($id, $params = null)
    {
        return $this->json('GET', "/api/achievements/$id" . $params);
    }

    /** @test */
    public function testReturnsAchievementDetailsForGivenID()
    {
        $this->withoutExceptionHandling();

        $achievement = factory(Achievement::class)->create();

        $response = $this->readModel($achievement->id);

        $response->assertStatus(200)->assertExactJson([
            'id' => $achievement->id,
            'blurb' => $achievement->blurb,
            'job_id' => $achievement->job_id,
            'created_at' => $achievement->created_at->toDateTimeString(),
            'updated_at' => $achievement->updated_at->toDateTimeString()
        ]);
    }

    /** @test */
    public function testReturnsAchievementDetailsWithTheJob()
    {
        $this->withoutExceptionHandling();

        $job = factory(Job::class)->create();
        $achievement = factory(Achievement::class)->create([
            'job_id' => $job->id
        ]);

        $response = $this->readModel($achievement->id, '?include=job');

        $response->assertStatus(200)->assertJson([
            'job' => $job->toArray()
        ]);
    }

    /** @test */
    public function testReturns404ErrorIfNoModelExists()
    {
        factory(Achievement::class)->create();

        $response = $this->readModel(22);

        $response->assertStatus(404);
        $this->assertEquals(1, Achievement::count());
    }
}
