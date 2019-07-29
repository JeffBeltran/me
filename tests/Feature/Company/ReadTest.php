<?php

namespace Tests\Feature\Company;

use App\Job;
use App\User;
use App\Company;
use Tests\TestCase;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadTest extends TestCase
{
    use RefreshDatabase;

    private function readModel($id, $params = null)
    {
        return $this->json('GET', "/api/companies/$id" . $params);
    }

    /** @test */
    public function testReturnsCompanyDetailsForGivenID()
    {
        $this->withoutExceptionHandling();

        $company = factory(Company::class)->create();

        $response = $this->readModel($company->id);

        $response->assertStatus(200)->assertExactJson([
            'id' => $company->id,
            'name' => $company->name,
            'created_at' => $company->created_at->toDateTimeString(),
            'updated_at' => $company->updated_at->toDateTimeString()
        ]);
    }

    /** @test */
    public function testReturnsCompanyDetailsWithTheirJobs()
    {
        $this->withoutExceptionHandling();

        $company = factory(Company::class)->create();
        factory(Job::class, 3)->create([
            'company_id' => $company->id
        ]);

        $response = $this->readModel($company->id, '?include=jobs');

        $response->assertStatus(200)->assertJsonCount(3, 'jobs');
    }

    /** @test */
    public function testReturns404ErrorIfNoModelExists()
    {
        factory(Company::class)->create();

        $response = $this->readModel(22);

        $response->assertStatus(404);
        $this->assertEquals(1, Company::count());
    }
}
