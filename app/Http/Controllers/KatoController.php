<?php

namespace App\Http\Controllers;

use App\Models\Kato;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class KatoController extends Controller
{

    /**
     * Get all active (Not ended) katos
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getActual(Request $request): JsonResponse
    {
        $katos = Kato::whereNull('end_date')->paginate(50);
        if($katos->isEmpty()){
            return response()->json(['message' => 'data not found'], 404);
        }

        return response()->json(['data' => $katos]);
    }

    /**
     * Search Kato by "te" column
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'query' => 'required|min:2',
        ]);
        $katos = Kato::where('te', 'like', $validated['query'].'%')->get();
        if($katos->isEmpty()){
            return response()->json(['message' => 'data not found'], 404);
        }

        return response()->json(['data' => $katos]);
    }

    /**
     * Get children records by "te"
     *
     * @param Request $request
     * @param $te
     * @return JsonResponse
     */
    public function getTree(Request $request, $te): JsonResponse
    {
        $katos = Kato::where('te', $te)->with('children' )->get();

        if($katos->isEmpty()){
            return response()->json(['message' => 'data not found'], 404);
        }

        return response()->json(['data' => $katos]);
    }

}
