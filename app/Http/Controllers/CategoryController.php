<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth", 
 *     type="http", 
 *     scheme="bearer", 
 *     bearerFormat="sanctum",
 *     description="JWT Bearer authentication"
 * )
 */

class CategoryController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/categories",
     *     summary="Get all categories",
     *     security={{"bearerAuth": {}}},
     *     tags={"Category"},
     *     @OA\Response(
     *         response=200,
     *         description="List of categories",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Category")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function index()
    {
        $category = Category::all();
        return CategoryResource::collection($category); 
    }

    /**
     * @OA\Get(
     *     path="/api/categories/{id}",
     *     summary="Get a category by id",
     *     tags={"Category"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The id of the category",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category found",
     *         @OA\JsonContent(ref="#/components/schemas/Category")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found"
     *     )
     * )
     */
    public function show($id)
    {
        $category = Category::findOrFail($id); // Find category by id
        return new CategoryResource($category);
    }


    /**
     * @OA\Post(
     *     path="/api/categories",
     *     summary="Create a new category",
     *     tags={"Category"},
     *      security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "eID"},
     *             @OA\Property(property="title", type="string", example="New Category"),
     *             @OA\Property(property="eID", type="integer", example=123)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Category created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Category")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     */
    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create($request->validated());
        return $this->success(new CategoryResource($category));
    }
    /**
     * @OA\Put(
     *     path="/api/categories/{id}",
     *     summary="Update a category by id",
     *     tags={"Category"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The id of the category to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "eID"},
     *             @OA\Property(property="title", type="string", example="Updated Category"),
     *             @OA\Property(property="eID", type="integer", example=456)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Category")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     */
    public function update(UpdateCategoryRequest $request, $id)
    {
        $category = Category::findOrFail($id); // Using findOrFail here for clarity.
        $category->update($request->validated());
        return new CategoryResource($category);
    }


   /**
     * @OA\Delete(
     *     path="/api/categories/{id}",
     *     summary="Delete a category by id",
     *     security={{"bearerAuth": {}}},
     *     tags={"Category"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The id of the category to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        // Find the category by its ID or fail
        $category = Category::findOrFail($id);

        // Delete the category
        $category->delete();

        // Return a successful response (optional, can include a message if desired)
        return response()->json(['message' => 'Category deleted successfully.'], 200);
    }

}
