<?php

namespace Tests\Feature\School;

use Tests\TestCase;
use App\School;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BrowseTest extends TestCase
{
    use RefreshDatabase;

    private function browseModels($params = null)
    {
        $response = $this->json('GET', '/api/schools' . $params);

        return $response;
    }

    /** @test */
    public function testUserCanBrowseSchools()
    {
        $this->withoutExceptionHandling();

        factory(School::class, 5)->create();

        $response = $this->browseModels();

        $response->assertStatus(200)->assertJsonCount(5);
    }

    /** @test */
    function testSortSchoolsByName()
    {
        $this->withoutExceptionHandling();

        $schoolOne = factory(School::class)->create([
            'name' => 'Alpha'
        ]);
        $schoolTwo = factory(School::class)->create([
            'name' => 'Zulu'
        ]);
        $schoolThree = factory(School::class)->create([
            'name' => 'Hotel'
        ]);

        $response = $this->browseModels('?sort=name,asc');

        $response->assertStatus(200)->assertJsonCount(3);

        $returnedData = collect($response->json());
        $this->assertEquals($schoolOne->name, $returnedData->first()['name']);
        $this->assertEquals($schoolTwo->name, $returnedData->last()['name']);
    }
}
