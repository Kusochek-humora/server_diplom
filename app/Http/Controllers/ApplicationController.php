<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *     title="API для работы с заявками",
 *     version="1.0.0",
 *     description="API для управления заявками на обслуживание",
 *     @OA\Contact(
 *         email="support@example.com",
 *         name="Support Team"
 *     )
 * )
 */

/**
 * @OA\Get(
 *     path="/api/applications",
 *     summary="Get all applications",
 *     @OA\Response(response="200", description="List of applications")
 * )
 *
 * @OA\Post(
 *     path="/api/applications",
 *     summary="Create a new application",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(ref="#/components/schemas/ApplicationRequest")
 *     ),
 *     @OA\Response(response="201", description="Application created successfully"),
 *     @OA\Response(response="422", description="Validation error")
 * )
 *
 * @OA\Get(
 *     path="/api/applications/{id}",
 *     summary="Get an application by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response="200", description="Application found"),
 *     @OA\Response(response="404", description="Application not found")
 * )
 *
 * @OA\Put(
 *     path="/api/applications/{id}",
 *     summary="Update an application by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(ref="#/components/schemas/ApplicationRequest")
 *     ),
 *     @OA\Response(response="200", description="Application updated successfully"),
 *     @OA\Response(response="404", description="Application not found"),
 *     @OA\Response(response="422", description="Validation error")
 * )
 *
 * @OA\Delete(
 *     path="/api/applications/{id}",
 *     summary="Delete an application by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response="200", description="Application deleted successfully"),
 *     @OA\Response(response="404", description="Application not found")
 * )
 *
 * @OA\Schema(
 *     schema="ApplicationRequest",
 *     required={"name", "phone", "email", "question", "category", "file_path", "date"},
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="phone", type="string", example="1234567890"),
 *     @OA\Property(property="telegram", type="string", example="john_doe"),
 *     @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *     @OA\Property(property="question", type="string", example="Question content"),
 *     @OA\Property(property="category", type="string", example="Category"),
 *     @OA\Property(property="file_path", type="string", example="https://example.com/file.pdf"),
 *     @OA\Property(property="date", type="string", format="date", example="2024-04-30")
 * )
 */

class ApplicationController extends Controller
{
    public function index()
    {
        return response()->json(Application::all());
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'telegram' => 'string|max:255',
            'email' => 'required|email|max:255',
            'question' => 'required|string',
            'category' => 'required|string|max:255',
            'file' => 'required|file|max:10240', // Максимальный размер файла 10 Мб
            'date' => 'required|date',
        ]);

        // Сохраняем файл на сервере
        $file = $request->file('file');
        $filePath = $file->store('uploads');

        $application = new Application();
        $application->name = $validatedData['name'];
        $application->phone = $validatedData['phone'];
        $application->telegram = $validatedData['telegram'];
        $application->email = $validatedData['email'];
        $application->question = $validatedData['question'];
        $application->category = $validatedData['category'];
        $application->file_path = $filePath;
        $application->date = $validatedData['date'];
        $application->save();

        return response()->json('Ok', 201);
    }


    public function show($id)
    {
        return response()->json(Application::findOrFail($id));
    }

    public function destroy($id)
    {
        $application = Application::findOrFail($id);


        $application->delete();

        return response()->json(['message' => 'Service deleted successfully']);
    }

    public function update(Request $request, $id)
    {
        // Валидация данных из запроса
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'telegram' => 'string|max:255',
            'email' => 'required|email|max:255',
            'question' => 'required|string',
            'category' => 'required|string|max:255',
            'file_path' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        $application = Application::findOrFail($id);

        // Обновляем данные записи
        $application->update([
            'name' => $validatedData['name'],
            'phone' => $validatedData['phone'],
            'telegram' => $validatedData['telegram'],
            'email' => $validatedData['email'],
            'question' => $validatedData['question'],
            'category' => $validatedData['category'],
            'file_path' => $validatedData['file_path'],
            'date' => $validatedData['date'],
        ]);

        return response()->json('Ok', 200);
    }
}
