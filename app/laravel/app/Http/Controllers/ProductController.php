<?php
/**
 * 사용자 페이지 상품 관련 기능 컨트롤러
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exceptions\HttpException;
use App\Models\Product;

class ProductController extends Controller
{
    public function productList(Request $request)
    {
        $filters = $request->only(['search']);
        $listQuery = Product::query()->where('status', 'a');

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $listQuery->where(function ($q) use ($search): void {
                $q->where('id', 'like', '%' . $search . '%')
                    ->orWhere('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        return view('product.list', ['productList' => $listQuery->paginate()]);
    }

    // 상품 보기 페이지
    public function productView(Request $request, int $id)
    {
        $product = Product::find($id);
        if (!$product || $product->status !== 'a') {
            throw new HttpException('상품을 찾을 수 없습니다.', 404);
        }
        
        // 내 포인트 가져오기
        $userPoints = $request->user()->userPointEntity()->getRemainPoints();

        return view('product.view', ['product' => $product, 'userPoints' => $userPoints]);
    }

    // 상품 주문


}