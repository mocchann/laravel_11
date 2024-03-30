<?php

use App\Models\Post;
use App\Models\User;
use Illuminate\Contracts\Database\Query\Builder;
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
    }

    /** @test */
    public function joinSub()
    {
        User::factory()->has(Post::factory())->create();
        User::factory()->has(Post::factory())->create();

        $latestPosts = DB::table('posts')
            ->select('user_id', DB::raw('MAX(created_at) as last_post_created_at'))
            ->groupBy('user_id');

        $users = DB::table('users')
            ->joinSub($latestPosts, 'latests_posts', function (JoinClause $join) {
                $join->on('users.id', '=', 'latests_posts.user_id');
            })
            ->get();
    }

    /** @test */
    public function joinLateral()
    {
        User::factory()->has(Post::factory()->count(2))->create();

        $latestPosts = DB::table('posts')
            ->select('id as post_id', 'created_at as post_created_at')
            ->whereColumn('user_id', 'users.id')
            ->orderBy('created_at', 'desc')
            ->limit(3);

        $users = DB::table('users')
            ->joinLateral($latestPosts, 'latest_posts')
            ->get();
    }

    /** @test */
    public function union()
    {
        User::factory()->has(Post::factory())->create();

        $first = DB::table('users');
        $union = DB::table('users')
            ->union($first)
            ->get();
    }

    /** @test */
    public function whereArray()
    {
        User::factory()->create();
        $user = DB::table('users')
            ->where([
                ['id', '>', 1],
            ])
            ->get();
    }

    /** @test */
    public function orWhere()
    {
        User::factory()->create();
        User::factory()->create();
        User::factory()->create();
        $users = DB::table('users')
            ->where('id', '>', 1)
            ->orWhere(function (Builder $query) {
                $query->where('email', 'like', '%net');
            })
            ->get();
        dd($users);
    }
}
