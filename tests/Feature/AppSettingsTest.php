<?php

namespace Squadron\AppSettings\Tests\Feature;

use Squadron\AppSettings\Tests\TestCase;

class AppSettingsTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        config()->set('squadron.appSettings', [
            'test-setting-1' => [
                'default' => 'This is test settings 1 value',
                'rules' => 'string'
            ],
            'test-setting-2' => [
                'default' => 'This is test settings 2 value',
                'rules' => 'string'
            ],
            'test-setting-3' => [
                'default' => 'This is test settings 3 value',
                'rules' => 'string'
            ]
        ]);
    }

    public function testGetList(): void
    {
        $response = $this->get('/api/settings');
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['test-setting-1', 'test-setting-2', 'test-setting-3']
            ]);
    }

    public function testGetFilteredList(): void
    {
        $response = $this->get('/api/settings/test-setting-1,test-setting-2');
        $response
            ->assertStatus(200)
            ->assertJsonMissing(['test-setting-3' => 'This is test settings 3 value'])
            ->assertJsonStructure([
                'data' => ['test-setting-1', 'test-setting-2']
            ]);
    }

    public function testSetWithoutAccess(): void
    {
        // unauthenticated
        $response = $this->json('POST', '/api/settings', [
            'test-setting-1' => 'New test settings 1 value'
        ]);

        $response
            ->assertStatus(401)
            ->assertJson(['success' => false, 'message' => 'Unauthenticated.']);

        // unauthorized
        $response = $this->actingAsRole('user')->json('POST', '/api/settings', [
            'test-setting-1' => 'New test settings 1 value'
        ]);

        $response
            ->assertStatus(401)
            ->assertJson(['success' => false, 'message' => 'This action is unauthorized.']);
    }

    public function testSetWithAccess(): void
    {
        // change settings
        $response = $this->actingAsRole('root')->json('POST', '/api/settings', [
            'test-setting-1' => 'New test settings 1 value'
        ]);

        $response->assertJson(['success' => true]);

        // check new setting's value
        $response = $this->get('/api/settings/test-setting-1');
        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => ['test-setting-1' => 'New test settings 1 value']
            ]);
    }
}
