<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Domains\Queries\ProductQuery;

class ProductController extends Controller
{
    public function productList(Request $request, ProductQuery $productQuery)
    {
        $filters = $request->only(['search']);
        $productList = $productQuery->listQueryForAdmin($filters)->paginate(10);

        return view('admin.product_list', ['productList' => $productList]);
    }

    // 상품 편집 페이지
    public function productEditPage(Request $request)
    {
        $product = null;
        if ($productId = $request->query('id')) {
            $product = Product::find($productId);
            
        }

        return view('admin.product_edit', ['product' => $product]);
    }

    // 상품 저장 (생성/수정)
    public function productStore(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        // 이 후에 더 많은 로직이 필요하다면 도메인 모델이나 도메인 서비스로 이동할 수도 있다.
        $productModel = new Product();
        $productModel->id = $validatedData['id'] ?? null;
        $productModel->name = $validatedData['name'];
        $productModel->price = $validatedData['price'];
        $productModel->stock = $validatedData['stock'];
        $productModel->description = $validatedData['description'];
        $productModel->status = $productModel->status ?? 'a';
        $productModel->save();

        return redirect()->route('admin.product_list');
    }

}