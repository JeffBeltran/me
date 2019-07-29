<?php

namespace Tests\Feature\Buzzword;

use Tests\TestCase;
use App\Buzzword;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BrowseTest extends TestCase
{
    use RefreshDatabase;

    private function browseModels($params = null)
    {
        $response = $this->json('GET', '/api/buzzwords' . $params);

        return $response;
    }

    /** @test */
    public function testUserCanBrowseBuzzwords()
    {
        $this->withoutExceptionHandling();

        factory(Buzzword::class, 5)->create();

        $response = $this->browseModels();

        $response->assertStatus(200)->assertJsonCount(5);
    }

    /** @test */
    function testSortBuzzwordsByName()
    {
        $this->withoutExceptionHandling();

        $buzzwordOne = factory(Buzzword::class)->create([
            'word' => 'Alpha'
        ]);
        $buzzwordTwo = factory(Buzzword::class)->create([
            'word' => 'Zulu'
        ]);
        $buzzwordThree = factory(Buzzword::class)->create([
            'word' => 'Hotel'
        ]);

        $response = $this->browseModels('?sort=word,asc');

        $response->assertStatus(200)->assertJsonCount(3);

        $returnedData = collect($response->json());
        $this->assertEquals($buzzwordOne->word, $returnedData->first()['word']);
        $this->assertEquals($buzzwordTwo->word, $returnedData->last()['word']);
    }
}
