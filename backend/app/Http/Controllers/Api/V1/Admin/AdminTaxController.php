<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTaxRequest;
use App\Http\Requests\Admin\UpdateTaxRequest;
use App\Http\Resources\Taxes\TaxResource;
use App\Models\Tax;
use Illuminate\Support\Facades\DB;

class AdminTaxController extends Controller
{
    public function index()
    {
        return TaxResource::collection(Tax::query()->orderBy('name')->get());
    }

    public function store(StoreTaxRequest $request)
    {
        $tax = DB::transaction(function () use ($request) {
            $data = $request->validated();

            if (! empty($data['is_default'])) {
                Tax::query()->where('is_default', true)->update(['is_default' => false]);
            }

            return Tax::create($data);
        });

        return (new TaxResource($tax))
            ->additional(['message' => __('api.admin.tax_created')])
            ->response()
            ->setStatusCode(201);
    }

    public function show(Tax $tax)
    {
        return new TaxResource($tax);
    }

    public function update(UpdateTaxRequest $request, Tax $tax)
    {
        $tax = DB::transaction(function () use ($request, $tax) {
            $data = $request->validated();

            if (! empty($data['is_default'])) {
                Tax::query()
                    ->where('is_default', true)
                    ->where('id', '!=', $tax->id)
                    ->update(['is_default' => false]);
            }

            $tax->update($data);

            return $tax->fresh();
        });

        return (new TaxResource($tax))
            ->additional(['message' => __('api.admin.tax_updated')]);
    }

    public function destroy(Tax $tax)
    {
        if ($tax->is_default) {
            return response()->json([
                'message' => __('api.admin.tax_delete_default'),
            ], 422);
        }

        if ($tax->products()->exists()) {
            return response()->json([
                'message' => __('api.admin.tax_delete_has_products'),
            ], 422);
        }

        $tax->delete();

        return response()->noContent();
    }
}
