<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;


/**
 * @OA\Tag(
 *     name="Products",
 *     description="API Endpoints for product management"
 * )
 * 
 * @OA\Schema(
 *     schema="ProductRequest",
 *     required={"name", "description", "price", "stock"},
 *     @OA\Property(property="name", type="string", example="Product Name"),
 *     @OA\Property(property="description", type="string", example="Product Description"),
 *     @OA\Property(property="price", type="number", format="float", example=99.99),
 *     @OA\Property(property="stock", type="integer", example=100)
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     in="header",
 *     name="Authorization",
 *     description="JWT Authorization header using the Bearer scheme. Example: 'Bearer {token}'"
 * )
 */
class ProductController extends Controller
{
    public function __construct()
    {
        // Apply the 'admin' middleware to the store, update, and destroy methods
        $this->middleware('admin')->only(['store', 'update', 'destroy']);
    }
    // GET /api/products
    /**
     * @OA\Get(
     *     path="/api/products",
     *     tags={"Products"},
     *     summary="Get all products",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of products"
     *     )
     * )
     */

    public function index()
    {
        return Product::all();
    }

    // POST /api/products (admin only)
    /**
     * @OA\Post(
     *     path="/api/products",
     *     tags={"Products"},
     *     summary="Create a new product",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ProductRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Product created successfully"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized: Only admins can perform this action"
     *     )
     * )
     */

    public function store(StoreProductRequest $request)
    {
        Log::info('Store Product Request:', $request->validated());
        $product = Product::create($request->validated());
        return response()->json($product, Response::HTTP_CREATED);
    }

    // PUT /api/products/{id} (admin only)
    /**
     * @OA\Put(
     *     path="/api/products/{id}",
     *     tags={"Products"},
     *     summary="Update an existing product",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the product to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ProductRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product updated successfully"
     *     )
     * )
     */

    public function update(UpdateProductRequest $request, $id)
    {
        Log::info("Update Product ID: $id", $request->validated());
        $product = Product::findOrFail($id);
        $product->update($request->validated());
        return response()->json($product);
    }

    // DELETE /api/products/{id} (admin only)
    /**
     * @OA\Delete(
     *     path="/api/products/{id}",
     *     tags={"Products"},
     *     summary="Delete a product",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the product to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Product deleted successfully"
     *     )
     * )
     */
    public function destroy($id)
    {
        Log::info("Delete Product ID: $id");
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
