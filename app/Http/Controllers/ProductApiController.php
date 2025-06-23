<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Interfaces\ProductRepositoryInterface;
use App\Classes\ApiResponseClass;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\DB;

class ProductApiController extends Controller
{
    private ProductRepositoryInterface $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function index()
    {
        $data = $this->productRepository->index();
        return ApiResponseClass::sendResponse(
            ProductResource::collection($data),
            'Products retrieved successfully',
            200
        );
    }

    public function store(StoreProductRequest $request)
    {
        DB::beginTransaction();
        try {
            $product = new Product();
            $product->name = $request->name;
            $product->details = $request->details;
            $product->save();
            DB::commit();
            return ApiResponseClass::sendResponse(
                new ProductResource($product),
                'Product created successfully',
                201
            );
        } catch (\Exception $e) {
            return ApiResponseClass::rollback($e, 'An error occurred while creating the product.');
        }
    }

    public function show(Product $product)
    {
        return ApiResponseClass::sendResponse(
            new ProductResource($product),
            'Product retrieved successfully',
            200
        );
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        DB::beginTransaction();
        try {
            $product->name = $request->name;
            $product->details = $request->details;
            $product->save();
            DB::commit();
            return ApiResponseClass::sendResponse(
                new ProductResource($product),
                'Product updated successfully',
                200
            );
        } catch (\Exception $e) {
            return ApiResponseClass::rollback($e, 'An error occurred while updating the product.');
        }
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return ApiResponseClass::sendResponse(
            null,
            'Product deleted successfully',
            200
        );
    }
}
