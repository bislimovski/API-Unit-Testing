<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SectorTest extends TestCase
{
    use DatabaseTransactions;

    protected $modelUrl = 'sectors';

    /** @test */
    public function return_code_200_method_index_if_data_is_empty()
    {
        $this->visit($this->url())
            ->see('"data": []');
        $this->assertResponseOk();
    }

    /** @test */
    public function return_code_200_method_index_and_check_for_pagination()
    {
        $this->get($this->url());
        $this->isJson();
        $this->assertResponseOk();

        $response = json_decode($this->response->getContent(), true);
        $this->assertArrayHasKey('total', $response);
        $this->assertArrayHasKey('data', $response);
    }

    /** @test */
    public function return_code_200_method_index_and_check_for_pagination_another_way()
    {
        $this->call('GET', $this->url());
        $this->assertResponseOk();
        $this->isJson();

        $response = json_decode($this->response->getContent(), true);
        $checkForPagination = isset($response['total']) ? true : false;
        $this->assertEquals(true, $checkForPagination);
    }

    /** @test */
    public function return_code_201_method_create()
    {
        $createSector = factory(App\Sector::class)->create();

        $post = $this->call('POST', $this->url(), $createSector->toArray());

        $this->assertEquals(201, $post->status());
        $this->seeInDatabase($createSector->getTable(), $createSector->toArray());
    }

    /** @test */
    public function return_code_200_method_show()
    {
        $sector = App\Sector::first();

        $get = $this->call('GET', $this->url($sector->id));
        $this->assertEquals(200, $get->status());
    }

    /** @test */
    public function return_code_202_method_update()
    {
        $createSector = factory(App\Sector::class)->create();
        $createSector->name = 'UpdateName';
        $createSector->save();

        $get = $this->call('PUT', $this->url($createSector->id), $createSector->toArray());
        $this->assertEquals(202, $get->status());
        $this->seeInDatabase($createSector->getTable(), $createSector->toArray());
    }

    /** @test */
    public function return_code_204_method_destroy()
    {
        $createSector = factory(App\Sector::class)->create();

        $delete = $this->call('DELETE', $this->url($createSector->id), $createSector->toArray());
        $this->assertEquals(204, $delete->status());
        $this->notSeeInDatabase($createSector->getTable(), $createSector->toArray());
    }

    /** @test */
    public function return_code_422_if_data_is_not_successfully_created()
    {
        $createSector = factory(App\Sector::class)->create([
            'name' => 'Test',
            'year' => 2015,
            'revenue' => str_random(10)
        ]);
        $createSectorArray = $createSector->toArray();
        unset($createSectorArray['name']);

        $this->post($this->url(), $createSectorArray);
        $this->assertResponseStatus(422);
        $this->notSeeInDatabase($createSector->getTable(), $createSectorArray);
    }

    /** @test */
    public function return_code_422_if_data_is_not_successfully_updated()
    {
        $createSector = factory(App\Sector::class)->create()->toArray();
        $createSector['name'] = '';

        $this->put($this->url($createSector['id']), $createSector);
        $this->assertResponseStatus(422);
    }

    /** @test */
    public function return_code_404_if_record_not_exist()
    {
        $this->get($this->url(0));
        $this->assertResponseStatus(404);
    }

}
