<?php

namespace App\Http\Controllers;
use App\Http\Requests\UserRequest;
use App\Http\Resources\CurrencyCollection;
use App\Http\Resources\CurrencyResource;
use App\Models\Currency;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CurrencyController extends Controller
{
    /**
     * Create user and take Token
     *
     * @param UserRequest $request
     *
     * @return JsonResponse
     */
    public function createUser(UserRequest $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $token = $user->createToken('userToken');

        return response()->json(['token' => $token]);
    }

    /**
     * Get all currencies by date
     *
     * @param Request $request
     * @param $date
     * @return JsonResponse
     */
    public function getCurrencies(Request $request, $date): JsonResponse
    {
        $currencies = Currency::whereDate('date', '=', Carbon::parse($date))->paginate(10);
        if($currencies->isEmpty()){
            return response()->json(['message' => 'data not found'], 404);
        }

        return response()->json(['currencies' => new CurrencyCollection($currencies)]);
    }

    /**
     * Get currency by name
     *
     * @param Request $request
     * @param $name
     * @return JsonResponse
     */
    public function getCurrency(Request $request, $name): JsonResponse
    {
        $date = $request->date ? Carbon::parse($request->date) : Carbon::now();
        $currency = Currency::where('name', '=', $name)->whereDate('date', '=', $date)->first();
        if(!$currency){
            return response()->json(['message' => 'data not found'], 404);
        }

        return response()->json(['currency' => new CurrencyResource($currency)]);
    }

}
