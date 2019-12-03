<?php

namespace Tests\Feature;

use Core\Models\IP;
use Core\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IPControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::findOrFail(1));
    }

    public function testItLoadsTheIpIndexPage()
    {
        $response = $this->get('/configuration/ip');
        $response->assertViewIs('ip.index')
            ->assertViewHas([
                'ips' => IP::paginate(25)
            ])
            ->assertStatus(200);
    }

    public function testItLoadsTheIpCreatePage()
    {
        $response = $this->get('/configuration/ip/create');
        $response->assertViewIs('ip.create')
            ->assertStatus(200);
    }

    /**
     * @dataProvider dataIPs
     */
    public function testItSavesTheIps($type, $addr)
    {
        $response = $this->post('/configuration/ip', [
            'type' => $type,
            'address' => $addr
        ]);

        $this->assertDatabaseHas('system_ips', [
            'type' => $type,
            'address' => $addr
        ]);

        $response->assertRedirect(route('core.ip.index'));
    }

    public function dataIPs()
    {
        return [
            'ipv6' => ['ipv6', '2a00:1450:4007:80f::200e'],
            'ipv4' => ['ipv4', '1.1.1.1']
        ];
    }
}
