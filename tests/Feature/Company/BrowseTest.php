<?php

namespace Tests\Feature\Company;

use App\Job;
use App\User;
use App\Company;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BrowseTest extends TestCase
{
    use RefreshDatabase;

    private function browseModels($params = null)
    {
        $response = $this->json('GET', '/api/companies' . $params);

        return $response;
    }

    /** @test */
    public function testUserCanBrowseCompanies()
    {
        $this->withoutExceptionHandling();

        factory(Company::class, 5)->create();

        $response = $this->browseModels();

        $response->assertStatus(200)->assertJsonCount(5);
    }

    /** @test */
    public function testReturnsCompanysWithTheirJobsRelationship()
    {
        $this->withoutExceptionHandling();

        factory(Company::class, 5)
            ->create()
            ->each(function ($company) {
                $company->jobs()->save(factory(Job::class)->create());
            });

        $response = $this->browseModels('?include=jobs');

        $response->assertStatus(200)->assertJsonCount(5);

        collect($response->json())->each(function ($company) {
            $this->assertNotEmpty(
                $company['jobs'],
                "An company is missing the relationship"
            );
        });
    }
}
