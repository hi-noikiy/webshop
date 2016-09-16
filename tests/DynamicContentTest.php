<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class DynamicContentTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testNewsOnHomepage()
    {
        $message = 'This is a test message';

        $content = \App\Content::where('name', 'home.news')->first();
        $content->content = $message;
        $content->save();

        $this->visit('/')->see($message);
    }
}
