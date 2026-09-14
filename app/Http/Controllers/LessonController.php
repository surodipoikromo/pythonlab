<?php
namespace App\Http\Controllers;
use Illuminate\View\View;
class LessonController
{
    public function index(): View
    {
        return view('home', ['lessons' => config('lessons')]);
    }
    public function show(string $slug): View
    {
        $lessons = collect(config('lessons'));
        $lesson = $lessons->firstWhere('slug', $slug);
        abort_unless($lesson, 404);
        $index = $lessons->search(fn ($item) => $item['slug'] === $slug);
        return view('lesson', [
            'lesson' => $lesson,
            'number' => $index + 1,
            'total' => $lessons->count(),
            'previous' => $index > 0 ? $lessons[$index - 1] : null,
            'next' => $index < $lessons->count() - 1 ? $lessons[$index + 1] : null,
        ]);
    }
}
