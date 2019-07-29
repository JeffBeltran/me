<?php

namespace Tests\Feature\School;

use Tests\TestCase;
use App\School;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadTest extends TestCase
{
    use RefreshDatabase;

    private function readModel($id, $params = null)
    {
        return $this->json('GET', "/api/schools/$id" . $params);
    }

    /** @test */
    public function testReturnsSchoolDetailsForGivenID()
    {
        $this->withoutExceptionHandling();

        $school = factory(School::class)->create();

        $response = $this->readModel($school->id);

        $response->assertStatus(200)->assertExactJson([
            'id' => $school->id,
            'name' => $school->name,
            'level' => $school->level,
            'category' => $school->category,
            'specialty' => $school->specialty,
            'graduated' => $school->graduated->toDateTimeString(),
            'created_at' => $school->created_at->toDateTimeString(),
            'updated_at' => $school->updated_at->toDateTimeString()
        ]);
    }

    /** @test */
    public function testReturnsSchoolDetailsForNullableFields()
    {
        $this->withoutExceptionHandling();

        $school = factory(School::class)->create([
            'level' => null,
            'category' => null,
            'specialty' => null,
            'graduated' => null
        ]);

        $response = $this->readModel($school->id);

        $response->assertStatus(200)->assertExactJson([
            'id' => $school->id,
            'name' => $school->name,
            'level' => $school->level,
            'category' => $school->category,
            'specialty' => $school->specialty,
            'graduated' => $school->graduated,
            'created_at' => $school->created_at->toDateTimeString(),
            'updated_at' => $school->updated_at->toDateTimeString()
        ]);
    }

    /** @test */
    public function testReturns404ErrorIfNoModelExists()
    {
        factory(School::class)->create();

        $response = $this->readModel(22);

        $response->assertStatus(404);
        $this->assertEquals(1, School::count());
    }
}
