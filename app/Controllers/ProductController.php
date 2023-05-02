<?php

namespace App\Controllers;

use App\Core\Response;
use App\Repositories\ProductRepository;
use App\Requests\AllProductRequest;
use App\Resources\ProductResource;
use Core\Pagination\Paginator;

class ProductController
{
    protected ProductRepository $productRepository;
    
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

   /**
     * Get a list of all products.
     *
     * @param AllProductRequest $request
     * @return Response
     */
    public function index(AllProductRequest $request)
    {
        $validatedData = $request->validated();

        $productRepository = new ProductRepository();
        $allProducts = $productRepository->index();
        $total = $allProducts->count();

        $sortedProducts = $productRepository->sort($allProducts, $validatedData);
        
        $productsResource = ProductResource::collection($sortedProducts);
        $paginatedProductData = (new Paginator($productsResource, $total))->toArray();
        
        return Response::sendWithCollection(true, HTTP_OK, 'Products retrieved successfully', $paginatedProductData);
    }
}
