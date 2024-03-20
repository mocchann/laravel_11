<?php

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class UserTest extends TestCase
{
  use DatabaseTransactions;

  /** @test */
  public function test()
  {
      User::factory()->create();

      $users = DB::table('users')->selectRaw('count(*) as user_count')->get();

      $this->assertSame($users->first()->user_count, 1);
  }

  /** @test */
  public function join()
  {
      User::factory()->has(Post::factory()->count(3))->create();
      $result = DB::table('users')
          ->join('posts', 'users.id', '=', 'posts.user_id')
          ->select('users.name', 'posts.tweet', 'users.email')
          ->get();
  }

  /** @test */
  public function leftJoin()
  {
      User::factory()->has(Post::factory()->count(2))->create();
      $result = DB::table('users')
          ->leftJoin('posts', 'users.id', '=', 'posts.user_id')
          ->get();
  }

  /** @test */
  public function rightJoin()
  {
      User::factory()->has(Post::factory()->count(2))->create();
      $result = DB::table('users')
          ->rightJoin('posts', 'users.id', '=', 'posts.user_id')
          ->get();
  }

  /** @test */
  public function crossJoin()
  {
      User::factory()->has(Post::factory()->count(2))->create();

      $result = DB::table('users')
          ->crossJoin('posts')
          ->get();
  }

  /** @test */
  public function joinClause()
  {
      User::factory()->has(Post::factory())->create();
      User::factory()->has(Post::factory())->create();
      User::factory()->has(Post::factory())->create();
      User::factory()->has(Post::factory())->create();
      $result = DB::table('users')
          ->join('posts', function (JoinClause $join) {
              $join->on('users.id', '=', 'posts.user_id')
                  ->where('posts.user_id', '>', 174);
          })
          ->get();

      dd($result);
  }
}
