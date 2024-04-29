<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *      title="Ян API",
 *      version="1.0.0",
 *      description="Моё API"
 *  )
 * @OA\Get(
 *     path="/api/services",
 *     summary="Get all services",
 *     @OA\Response(response="200", description="List of services")
 * )
 *
 * @OA\Post(
 *     path="/api/services",
 *     summary="Create a new service",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(ref="#/components/schemas/ServiceRequest")
 *     ),
 *     @OA\Response(response="201", description="Service created successfully"),
 *     @OA\Response(response="422", description="Validation error")
 * )
 *
 * @OA\Get(
 *     path="/api/services/{id}",
 *     summary="Get a service by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response="200", description="Service found"),
 *     @OA\Response(response="404", description="Service not found")
 * )
 *
 * @OA\Put(
 *     path="/api/services/{id}",
 *     summary="Update a service by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(ref="#/components/schemas/ServiceRequest")
 *     ),
 *     @OA\Response(response="200", description="Service updated successfully"),
 *     @OA\Response(response="404", description="Service not found"),
 *     @OA\Response(response="422", description="Validation error")
 * )
 *
 * @OA\Delete(
 *     path="/api/services/{id}",
 *     summary="Delete a service by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response="200", description="Service deleted successfully"),
 *     @OA\Response(response="404", description="Service not found")
 * )
 *
 * @OA\Schema(
 *     schema="ServiceRequest",
 *     required={"sort_order", "title", "description", "file_icon"},
 *     @OA\Property(property="sort_order", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Service Title"),
 *     @OA\Property(property="description", type="string", example="This is a description of the service."),
 *     @OA\Property(property="file_icon", type="string", example="https://example.com/icon.png")
 * )
 */


class ServiceController extends Controller
{
    public function index()
    {
        return response()->json(Service::all());
    }

    public function store(Request $request)
    {
        // Валидация данных из запроса
        $validatedData = $request->validate([
            'sort_order' => 'required|integer',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'file_icon' => 'required|string|max:255',
        ]);

        // Создание сервиса
        $service = Service::create([
            'sort_order' => $validatedData['sort_order'],
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'file_icon' => $validatedData['file_icon'],
        ]);

        return response()->json(['message' => 'Service created successfully'], 201);
    }

    public function show($id)
    {
        return response()->json(Service::findOrFail($id));
    }

    public function destroy($id)
    {
        $service = Service::findOrFail($id);

        // Удаление сервиса
        $service->delete();

        return response()->json(['message' => 'Service deleted successfully']);
    }
    public function update(Request $request, $id)
    {
        // Поиск сервиса по идентификатору
        $service = Service::findOrFail($id);

        // Валидация данных из запроса
        $validatedData = $request->validate([
            'sort_order' => 'required|integer',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'file_icon' => 'required|string|max:255',
        ]);

        // Обновление данных сервиса
        $service->update([
            'sort_order' => $validatedData['sort_order'],
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'file_icon' => $validatedData['file_icon'],
        ]);

        return response()->json(['message' => 'Service updated successfully']);
    }
}
