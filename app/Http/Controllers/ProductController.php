<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Interfaces\ProductRepositoryInterface;
use App\Classes\ApiResponseClass;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\DB;
class ProductController extends Controller
{
    private ProductRepositoryInterface $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->productRepository->index();

        return ApiResponseClass::sendResponse(
            ProductResource::collection($data),
            'Products retrieved successfully',
            200
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $details = [
            'name' => $request->name,
            'details' => $request->details
        ];

        DB::beginTransaction();
        try {
            $product = $this->productRepository->store($details);
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

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return ApiResponseClass::sendResponse(
            new ProductResource($product),
            'Product retrieved successfully',
            200
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $updateDetails = [
            'name' => $request->name,
            'details' => $request->details
        ];
        DB::beginTransaction();
        try {
            $product = $this->productRepository->update($product->id, $updateDetails);
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
