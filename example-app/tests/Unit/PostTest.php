<?php

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PostTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function test()
    {
        $user = User::factory()->has(Post::factory()->count(3))->create();

        $this->assertSame($user->posts->first()->tweet, Post::first()->tweet);
    }
}
