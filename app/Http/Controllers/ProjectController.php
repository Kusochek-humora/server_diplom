<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        return response()->json(\App\Models\Project::with('tag')->get());
    }

    public function store(Request $request)
    {
        // Валидация данных из запроса
        $validatedData = $request->validate([
            'sort_order' => 'required|integer',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'period' => 'required|string|max:255',
            'deadline' => 'required|string|max:255',
            'file_cover' => 'required|file|max:10240',
            'tags' => 'array', // Проверяем, что tags является массивом
        ]);
        $file = $request->file('file_cover');
        $filePath = $file->store('uploads');
        // Создание проекта
        $project = Project::create([
            'sort_order' => $validatedData['sort_order'],
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'period' => $validatedData['period'],
            'deadline' => $validatedData['deadline'],
            'file_cover' => $validatedData['file_cover'],
        ]);

        // Добавление тегов, если они предоставлены
        if (isset($validatedData['tags'])) {
            foreach ($validatedData['tags'] as $tag) {
                $project->tag()->create(['name' => $tag]);
            }
        }

        return response()->json(['message' => 'Project created successfully'], 201);
    }

    public function show($id)
    {
        return response()->json( Project::with('tag')->findOrFail($id));
    }
}
