<?php

use App\Models\User;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    public function test() {
        $user = User::first();
        $this->assertSame($user->name, 'Test User');
    }
}
