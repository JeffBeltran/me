<?php

namespace Tests\Feature\TemplateModelName;

use Tests\TestCase;
use App\TemplateModelName;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BrowseTest extends TestCase
{
    use RefreshDatabase;

    private function browseModels($params = null)
    {
        $response = $this->json('GET', '/api/templateModelNames' . $params);

        return $response;
    }

    /** @test */
    public function testUserCanBrowseTemplateModelNames()
    {
        $this->withoutExceptionHandling();

        factory(TemplateModelName::class, 5)->create();

        $response = $this->browseModels();

        $response->assertStatus(200)->assertJsonCount(5);
    }

    /** @test */
    function testSortTemplateModelNamesByName()
    {
        $this->withoutExceptionHandling();

        $templateModelNameOne = factory(TemplateModelName::class)->create([
            'name' => 'Alpha'
        ]);
        $templateModelNameTwo = factory(TemplateModelName::class)->create([
            'name' => 'Zulu'
        ]);
        $templateModelNameThree = factory(TemplateModelName::class)->create([
            'name' => 'Hotel'
        ]);

        $response = $this->browseModels('?sort=name,asc');

        $response->assertStatus(200)->assertJsonCount(3);

        $returnedData = collect($response->json());
        $this->assertEquals(
            $templateModelNameOne->name,
            $returnedData->first()['name']
        );
        $this->assertEquals(
            $templateModelNameTwo->name,
            $returnedData->last()['name']
        );
    }

    /** @test */
    public function testReturnsTemplateModelNamesWithTheirHasManyRelationshipNamesRelationship()
    {
        $this->withoutExceptionHandling();

        factory(TemplateModelName::class, 5)
            ->create()
            ->each(function ($templateModelName) {
                $templateModelName
                    ->hasManyRelationshipNames()
                    ->save(factory(HasManyRelationshipName::class)->create());
            });

        $response = $this->browseModels('?include=hasManyRelationshipNames');

        $response->assertStatus(200)->assertJsonCount(5);

        collect($response->json())->each(function ($templateModelName) {
            $this->assertNotEmpty(
                $templateModelName['hasManyRelationshipNames'],
                "An templateModelName is missing the relationship"
            );
        });
    }

    /** @test */
    public function testReturnsTemplateModelNamesWithTheBelongsToRelationshipNameTheyBelongsTo()
    {
        $this->withoutExceptionHandling();

        factory(TemplateModelName::class, 5)
            ->create()
            ->each(function ($templateModelName) {
                $templateModelName
                    ->belongsToRelationshipName()
                    ->associate(
                        factory(BelongsToRelationshipName::class)->create()
                    );
                $templateModelName->save();
            });

        $response = $this->browseModels('?include=belongsToRelationshipName');

        $response->assertStatus(200)->assertJsonCount(5);

        collect($response->json())->each(function ($templateModelName) {
            $this->assertNotEmpty(
                $templateModelName['belongsToRelationshipName'],
                "An templateModelName is missing the relationship"
            );
        });
    }

    /** @test */
    public function testReturnsTemplateModelNamesWithTheirBelongsToManyRelationshipNamesRelationship()
    {
        $this->withoutExceptionHandling();

        factory(TemplateModelName::class, 5)
            ->create()
            ->each(function ($templateModelName) {
                $templateModelName
                    ->belongsToManyRelationshipName()
                    ->attach([
                        factory(BelongsToManyRelationshipName::class)->create()
                            ->id,
                        factory(BelongsToManyRelationshipName::class)->create()
                            ->id
                    ]);
            });

        $response = $this->browseModels(
            '?include=belongsToManyRelationshipName'
        );

        $response->assertStatus(200)->assertJsonCount(5);

        collect($response->json())->each(function ($templateModelName) {
            $this->assertNotEmpty(
                $templateModelName['belongsToManyRelationshipName'],
                "An templateModelName is missing the relationship"
            );
            $this->assertCount(
                2,
                $templateModelName['belongsToManyRelationshipName']
            );
        });
    }
}
