<?php

namespace Tests\Feature;

use App\Models\Order;
use Database\Seeders\OrderSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Enums\Order\OrderStatusEnum;
use Database\Seeders\DatabaseSeeder;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function testAmountValuesInRange()
    {
        $this->seed(DatabaseSeeder::class);
        $data = $this->get('/api/backoffice/orders')->json();

        foreach ($data['data']['items'] as $item) {
            $this->assertGreaterThanOrEqual(0, $item['amount']);
            $this->assertLessThanOrEqual(999999, $item['amount']);
        }
    }

    public function testItemCountEqualsPaginationCount()
    {
        $this->seed(DatabaseSeeder::class);
        $data = $this->get('/api/backoffice/orders')->json();

        $this->assertEquals($data['data']['pagination']['count'], count($data['data']['items']));
    }

    public function testStatusValues()
    {
        $this->seed(DatabaseSeeder::class);
        $data = $this->get('/api/backoffice/orders')->json();

        foreach ($data['data']['items'] as $item) {
            $this->assertContains($item['status'], OrderStatusEnum::values());
        }
    }

    public function testSuccessIndex()
    {
        $this->seed(DatabaseSeeder::class);
        $response = $this->get('/api/backoffice/orders');

        $response->assertStatus(200);
        $response->assertJsonCount(4, 'data.items.0');
        $response->assertJsonCount(4, 'data.items.0.user');
        $response->assertJsonStructure([
            'message',
            'data' => [
                'items' => [
                    '*' => [
                        'id',
                        'amount',
                        'status',
                        'user' => [
                            'name',
                            'email',
                            'mobile',
                            'national_code',
                        ],
                    ],
                ],
                'pagination' => [
                    'total',
                    'count',
                    'perPage',
                    'currentPage',
                    'lastPage',
                ],
            ],
            'errors',
        ]);
    }

    public function testSuccessIndexFilterActiveStatus(): void
    {
        $validStatus = OrderStatusEnum::ACTIVE->value;

        $this->seed(DatabaseSeeder::class);
        $data = $this->get('/api/backoffice/orders?status=' . $validStatus)->json();

        foreach ($data['data']['items'] as $item) {
            $this->assertEquals($item['status'], $validStatus);
        }
    }

    public function testSuccessIndexFilterSpecialUserMobile(): void
    {
        $this->seed(DatabaseSeeder::class);
        $order = Order::inRandomOrder()->first();
        $response = $this->get('/api/backoffice/orders?mobile=' . $order->user->mobile);
        $data = $response->json();

        $this->assertNotEmpty($data['data']['items']);
    }
}
