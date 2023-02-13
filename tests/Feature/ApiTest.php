<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;


class ApiTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testRatesReturnsDataInValidFormat()
    {
        $token=config('constants.api_key');
        $response=$this->withHeaders(['Authorization'=>'Bearer '.$token,
            'Accept' => 'application/json'])
            ->get ('api/v1?method=rates');
        $response->assertJsonStructure([
            "status" ,
            "code" ,
            "data" ,
        ]);


    }

    public function testTokenValidation()
    {

        $response=$this->withHeaders(['Authorization'=>'Bearer ',
            'Accept' => 'application/json'])
            ->get ('api/v1?method=rates');
        $response->assertJsonStructure([
            "status" ,
            "code" ,
            "message" ,
        ])
        ->assertJson([
            'status' => 'error',
        ]);
    }
}
