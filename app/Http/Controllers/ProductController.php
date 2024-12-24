<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{


    /**
     * @OA\Get(
     *     path="/api/popular-products",
     *     summary="Get popular products containing 'A' in the title",
     *     tags={"Product"},
     *  security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of products",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Product"))
     *     ),
     * )
     */
    public function popularProducts()
    {
        $cacheKey = 'popular_products';
        
        // Замер времени выполнения до кеширования
        $startTime = microtime(true);
        
        // Проверяем, если данные уже в кеше, иначе получаем их
        $products = Cache::remember($cacheKey, 3600, function () {
            return Product::where('title', 'like', '%A%')->limit(10)->get();
        });

        // Замер времени выполнения после кеширования
        $executionTime = microtime(true) - $startTime;

        // Выводим время выполнения запроса
        debugbar()->info("Execution time: {$executionTime} seconds");

        return response()->json($products);
    }
    
    /**
     * @OA\Get(
     *     path="/api/products",
     *     summary="Get a list of all products",
     *     tags={"Product"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of all products",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Product")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function index()
    {
        $products = Product::all();
        return $this->success(ProductResource::collection($products->load('categories')));
    }

    /**
     * @OA\Post(
     *     path="/api/products",
     *     summary="Create a new product",
     *     tags={"Product"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "price", "eID"},
     *             @OA\Property(property="title", type="string", example="New Product"),
     *             @OA\Property(property="price", type="number", format="float", example=99.99),
     *             @OA\Property(property="eID", type="integer", example=123),
     *             @OA\Property(property="categories_id", type="array", @OA\Items(type="integer"), example={1,2}),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Product created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->validated());
        if ($request->has('categories_id')) {
            $product->categories()->attach($request->categories_id);
        }
        // event(new ProductCreatedOrUpdate($product));
        
        return $this->success(new ProductResource($product->load('categories')));
    }


    /**
     * @OA\Put(
     *     path="/api/products/{id}",
     *     summary="Update a product by ID",
     *     tags={"Product"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the product to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "price", "eID"},
     *             @OA\Property(property="title", type="string", example="Updated Product"),
     *             @OA\Property(property="price", type="number", format="float", example=79.99),
     *             @OA\Property(property="eID", type="integer", example=456),
     *             @OA\Property(property="categories_id", type="array", @OA\Items(type="integer"), example={2,3}),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function update(UpdateProductRequest $request, $id)
    {
        $validatedData = $request->validated();
        $product = Product::findOrFail($id);

        $product->update($validatedData);

        if ($request->has('categories_id')) {
            $product->categories()->sync($request->categories_id);
        }

        // send notification if necessary
        
        return $this->success(new ProductResource($product->load('categories')));
    }

    
    /**
     * @OA\Delete(
     *     path="/api/products/{id}",
     *     summary="Delete a product by ID",
     *     tags={"Product"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the product to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return $this->success([], __('product deleted successfully'));
    }

}
