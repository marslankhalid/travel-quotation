<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuotationRequest;
use App\Services\QuotationService;
use Illuminate\Http\JsonResponse;

class QuotationController extends Controller
{
    public function store(QuotationRequest $request, QuotationService $service): JsonResponse
    {
        try {
            $data = $service->calculate($request->validated());
            return response()->json($data, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}
