<?php

class EloquentAssertsTest extends TestCase
{
    /** @test */
    public function it_has_eloquent_table_equals_assertion()
    {
        $this->assertEloquentTableEquals(Post::class, 'posts');
    }

    /** @test */
    public function it_has_eloquent_table_not_equals_assertion()
    {
        $this->assertEloquentTableNotEquals(Post::class, 'users');
    }
}
