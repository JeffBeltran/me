<?php

namespace Tests\Feature\Achievement;

use App\Job;
use Tests\TestCase;
use App\Achievement;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BrowseTest extends TestCase
{
    use RefreshDatabase;

    private function browseModels($params = null)
    {
        $response = $this->json('GET', '/api/achievements' . $params);

        return $response;
    }

    /** @test */
    public function testUserCanBrowseAchievements()
    {
        $this->withoutExceptionHandling();

        factory(Achievement::class, 5)->create();

        $response = $this->browseModels();

        $response->assertStatus(200)->assertJsonCount(5);
    }

    /** @test */
    function testSortAchievementsByName()
    {
        $this->withoutExceptionHandling();

        $achievementOne = factory(Achievement::class)->create([
            'blurb' => 'Alpha'
        ]);
        $achievementTwo = factory(Achievement::class)->create([
            'blurb' => 'Zulu'
        ]);
        $achievementThree = factory(Achievement::class)->create([
            'blurb' => 'Hotel'
        ]);

        $response = $this->browseModels('?sort=blurb,asc');

        $response->assertStatus(200)->assertJsonCount(3);

        $returnedData = collect($response->json());
        $this->assertEquals(
            $achievementOne->blurb,
            $returnedData->first()['blurb']
        );
        $this->assertEquals(
            $achievementTwo->blurb,
            $returnedData->last()['blurb']
        );
    }

    /** @test */
    public function testReturnsAchievementsWithTheJobTheyBelongsTo()
    {
        $this->withoutExceptionHandling();

        factory(Achievement::class, 5)
            ->create()
            ->each(function ($achievement) {
                $achievement->job()->associate(factory(Job::class)->create());
                $achievement->save();
            });

        $response = $this->browseModels('?include=job');

        $response->assertStatus(200)->assertJsonCount(5);

        collect($response->json())->each(function ($achievement) {
            $this->assertNotEmpty(
                $achievement['job'],
                "An achievement is missing the relationship"
            );
        });
    }
}
