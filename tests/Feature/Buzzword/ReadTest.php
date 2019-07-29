<?php

namespace Tests\Feature\Buzzword;

use Tests\TestCase;
use App\Buzzword;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadTest extends TestCase
{
    use RefreshDatabase;

    private function readModel($id, $params = null)
    {
        return $this->json('GET', "/api/buzzwords/$id" . $params);
    }

    /** @test */
    public function testReturnsBuzzwordDetailsForGivenID()
    {
        $this->withoutExceptionHandling();

        $buzzword = factory(Buzzword::class)->create();

        $response = $this->readModel($buzzword->id);

        $response->assertStatus(200)->assertExactJson([
            'id' => $buzzword->id,
            'word' => $buzzword->word,
            'details' => $buzzword->details,
            'created_at' => $buzzword->created_at->toDateTimeString(),
            'updated_at' => $buzzword->updated_at->toDateTimeString()
        ]);
    }

    /** @test */
    public function testReturns404ErrorIfNoModelExists()
    {
        factory(Buzzword::class)->create();

        $response = $this->readModel(22);

        $response->assertStatus(404);
        $this->assertEquals(1, Buzzword::count());
    }
}
