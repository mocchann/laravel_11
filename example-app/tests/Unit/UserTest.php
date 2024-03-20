<?php

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class UserTest extends TestCase
{
  use DatabaseTransactions;

  public function test()
  {
      User::factory()->create();

      $users = DB::table('users')->selectRaw('count(*) as user_count')->get();

      $this->assertSame($users->first()->user_count, 1);
  }
}
