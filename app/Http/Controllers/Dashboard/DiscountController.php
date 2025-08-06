<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\DiscountRequest;
use App\Models\Discount;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class DiscountController extends Controller
{
    public function index(): LengthAwarePaginator
    {
        info(request()->filter);
        return Discount::filter(json_decode(request()->filter))->with('ranges')->paginate();
    }

    public function show(Discount $discount): Discount
    {
        return $discount->load('ranges', 'products:id,name,cover_image,price');
    }

    public function store(DiscountRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $data = $this->fillCommonDiscountData($request->validated());
            $discount = Discount::create($data);

            $this->syncProducts($discount, $request->product_ids);
            $this->syncRanges($discount, $request->ranges ?? [], $data['type']);

            DB::commit();

            return response()->json([
                'message' => 'Discount created',
                'discount' => $discount->load('products')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error creating discount: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(DiscountRequest $request, Discount $discount): JsonResponse
    {
        try {
            DB::beginTransaction();

            $data = $this->fillCommonDiscountData($request->validated());
            $discount->update($data);

            $this->syncProducts($discount, $request->product_ids);
            $discount->ranges()->delete(); // Clear old ranges before re-inserting
            $this->syncRanges($discount, $request->ranges ?? [], $data['type']);

            DB::commit();

            return response()->json(['message' => 'Discount updated']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error updating discount: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Discount $discount): JsonResponse
    {
        $discount->delete();
        return response()->json(['message' => 'Discount deleted']);
    }

    private function fillCommonDiscountData(array $data): array
    {
        $data['type'] = $data['discount_mode'] ?? 'fixed';
        $data['started_at'] = $data['starts_at'];
        $data['ended_at'] = $data['ends_at'];

        if ($data['type'] === 'fixed') {
            $data['discount_type'] = $data['type'];
            $data['discount_value'] = $data['value'];
        }

        return $data;
    }
    private function syncProducts(Discount $discount, ?array $productIds): void
    {
        if (!$productIds) {
            return;
        }
        $productIds = array_unique(array_filter($productIds));
        $discount->products()->sync($productIds);
    }

    private function syncRanges(Discount $discount, array $ranges, string $type): void
    {
        if ($type === 'ranged') {
            foreach ($ranges as $range) {
                $discount->ranges()->create([
                    'min_price' => $range['min'],
                    'max_price' => $range['max'],
                    'value' => $range['value'],
                    'type' => $range['type'] ?? 'fixed',
                ]);
            }
        }
    }
}
