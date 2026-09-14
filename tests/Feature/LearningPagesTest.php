<?php
namespace Tests\Feature;
use Tests\TestCase;
class LearningPagesTest extends TestCase
{
    public function test_home_page_lists_ten_modules(): void
    {
        $response = $this->get('/');
        $response->assertOk()->assertSee('10 modul inti')->assertSee('Hello World')->assertSee('Function');
    }
    public function test_each_lesson_is_accessible(): void
    {
        foreach (config('lessons') as $lesson) {
            $this->get('/belajar/'.$lesson['slug'])->assertOk()->assertSee($lesson['title']);
        }
    }
    public function test_unknown_lesson_returns_404(): void
    {
        $this->get('/belajar/tidak-ada')->assertNotFound();
    }
}
