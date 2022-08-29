<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Routing\Middleware\ThrottleRequests;
use Tests\TestCase;

class UserApiTest extends TestCase
{
  protected function setUp(): void
  {
    parent::setUp();
    $this->withoutMiddleware(
      ThrottleRequests::class
    );
  }

  public function testUserList()
  {
    $perPage = 20;

    for ($page = 1; $page <= 10; $page++) {

      for ($year = 1965; $year <= 2000; $year++) {

        for ($month = 1; $month <= 12; $month++) {

          // will be coming from database
          $resp1 = $this->json('GET', '/api/users', [
            'page' => $page,
            'perPage' => $perPage,
            'birth_year' => $year,
            'birth_month' => $month
          ])->json();

          // will be coming from redis
          $resp2 = $this->json('GET', '/api/users', [
            'page' => $page,
            'perPage' => $perPage,
            'birth_year' => $year,
            'birth_month' => $month
          ])->json();

          $itemsResp1 = count($resp1);
          $itemsResp2 = count($resp2);

          $this->assertTrue($itemsResp1 === $itemsResp2, 'They are not equal');
          $this->assertEquals($resp2, $resp1);
        }
      }
    }
  }
}
