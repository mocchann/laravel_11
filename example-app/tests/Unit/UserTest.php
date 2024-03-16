<?php

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    public function test() {
        $user = User::first();
        $this->assertSame($user->name, 'Test User');
    }
}
