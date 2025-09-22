<?php

namespace Tests\Feature\Users;

use Tests\TestCase;

class UserManagementTest extends TestCase
{
    public function test_users_index_route_is_registered(): void
    {
        $response = $this->get(route('users.index'));

        $response->assertOk();
        $response->assertSeeText('Users index');
    }
}

