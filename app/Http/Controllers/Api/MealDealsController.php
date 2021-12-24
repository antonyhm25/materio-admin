<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\MealDeal;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\MealDeals\MealDealResult;
use App\Http\Resources\MealDeals\MealDealPaginated;
use App\Models\Restaurant;

class MealDealsController extends Controller
{
    public function index(Request $request, Restaurant $restaurant)
    {
        $this->authorize('view', $restaurant);

        $request->validate([
            'page' => 'sometimes|required|integer',
            'size' => 'sometimes|required|integer',
            'sortBy' => 'sometimes|required|in:name,description,created_at',
            'order' => 'sometimes|required|in:asc,desc',
            'search' => 'sometimes|required',
            'address' => 'sometimes|required|string',
            'expired' => 'sometimes|required|boolean',
            'status' => 'sometimes|required|in:available,reserved,delivered',
        ]);

        try{
            $size = $request->size ?? 15;
            $sortBy = $request->sortBy ?? 'meal';
            $order = $request->order ?? 'asc';
            $search = $request->search ?? null;
            $address = $request->address ?? null;
            $expired = $request->expired ?? null;
            $status = $request->status ?? null;

            $query = MealDeal::select(
                'meal_deals.id',
                'meal_deals.price',
                'meal_deals.new_price as newPrice',
                'meal_deals.available',
                'meal_deals.status',
                'meal_deals.created_at as createdAt',
                'meals.name as meal',
                'meals.description',
                'restaurants.id as restaurantId',
                'restaurants.name as restaurant',
                'restaurants.address as location',
            )
            ->join('meals', 'meals.id', '=', 'meal_deals.meal_id')
            ->join('restaurants', 'restaurants.id', '=', 'meals.restaurant_id')
            ->where('restaurants.id', $restaurant->id)
            ->orderBy($sortBy, $order);

            if (!is_null($search)) {
                $query = $query->where('meals.name', 'like', "%$search%");
            }

            if (!is_null($status)) {
                $query = $query->where('meal_deals.status', $status);
            }
            
            if (!is_null($address)) {
                $query = $query->where('restaurants.address', 'like', "%$address%");
            }

            if (!is_null($expired)) {
                if ($expired) {
                    $query = $query->where('meal_deals.available', '<', now());
                } else {
                    $query = $query->where('meal_deals.available', '>', now());
                }
            }

            return new MealDealPaginated($query->paginate($size));
        } catch (Exception $ex){
            Log::error($ex);

            return response(trans('app.general.error'), 500);
        }
    }

    public function mealDeals(Request $request)
    {
        $this->authorize('viewAny', MealDeal::class);

        $request->validate([
            'page' => 'sometimes|required|integer',
            'size' => 'sometimes|required|integer',
            'sortBy' => 'sometimes|required|in:name,description,created_at',
            'order' => 'sometimes|required|in:asc,desc',
            'search' => 'sometimes|required',
            'restaurant' => 'sometimes|required|integer',
            'address' => 'sometimes|required|string',
            'expired' => 'sometimes|required|boolean',
            'status' => 'sometimes|required|in:available,reserved,delivered',
        ]);

        try{
            $size = $request->size ?? 15;
            $sortBy = $request->sortBy ?? 'meal';
            $order = $request->order ?? 'asc';
            $search = $request->search ?? null;
            $restaurant = $request->restaurant ?? null;
            $address = $request->address ?? null;
            $expired = $request->expired ?? null;
            $status = $request->status ?? null;

            $query = MealDeal::select(
                'meal_deals.id',
                'meal_deals.price',
                'meal_deals.new_price as newPrice',
                'meal_deals.available',
                'meal_deals.status',
                'meal_deals.created_at as createdAt',
                'meals.name as meal',
                'meals.description',
                'restaurants.id as restaurantId',
                'restaurants.name as restaurant',
                'restaurants.address as location',
            )
            ->join('meals', 'meals.id', '=', 'meal_deals.meal_id')
            ->join('restaurants', 'restaurants.id', '=', 'meals.restaurant_id')
            ->orderBy($sortBy, $order);

            if (!is_null($search)) {
                $query = $query->where('meals.name', 'like', "%$search%");
            }

            if (!is_null($restaurant)) {
                $query = $query->where('restaurants.id', $restaurant);
            }
            
            if (!is_null($status)) {
                $query = $query->where('meal_deals.status', $status);
            }
            
            if (!is_null($address)) {
                $query = $query->where('restaurants.address', 'like', "%$address%");
            }

            if (!is_null($expired)) {
                if ($expired) {
                    $query = $query->where('meal_deals.available', '<', now());
                } else {
                    $query = $query->where('meal_deals.available', '>', now());
                }
            }

            return new MealDealPaginated($query->paginate($size));
        } catch (Exception $ex){
            Log::error($ex);

            return response(trans('app.general.error'), 500);
        }
    }

    public function show(Restaurant $restaurant, MealDeal $meal)
    {
        $this->authorize('view', $restaurant);
        $this->authorize('view', $meal);

        try {
            return new MealDealResult($meal);
        } catch (Exception $ex) {
            Log::error($ex);

            return response(trans('app.general.error'), 500);
        }
    }

    public function store(Request $request, Restaurant $restaurant)
    {
        $this->authorize('create', $restaurant);

        $request->validate([
            'price' => 'nullable|numeric',
            'newPrice' => 'nullable|numeric',
            'available' => 'required|date',
            'mealId' => 'required|integer|exists:meals,id'
        ]);

        try {
            $folios = explode('-', Str::uuid());

            $meal = MealDeal::create([
                'folio' => $folios[1] .'-'. $folios[2],
                'price' => $request->price,
                'new_price' => $request->newPrice ?? 0.0,
                'available' => $request->available,
                'status' => 'available',
                'meal_id' => $request->mealId
            ]);

            return new MealDealResult($meal);
        } catch (Exception $ex) {
            Log::error($ex);

            return response(trans('app.general.error'), 500);
        }
    }

    public function update(Request $request, Restaurant $restaurant, MealDeal $meal)
    {
        $this->authorize('view', $restaurant);
        $this->authorize('update', $meal);

        $request->validate([
            'price' => 'nullable|numeric',
            'newPrice' => 'nullable|numeric',
            'available' => 'required|date',
            'status' => 'required|in:available,reserved,delivered'
        ]);

        try {
            $meal->fill([
                'price' => $request->price,
                'new_price' => $request->newPrice ?? 0.0,
                'available' => $request->available,
                'status' => $request->status
            ]);

            $meal->save();

            return response(null, 204);
        } catch (Exception $ex) {
            Log::error($ex);

            return response(trans('app.general.error'), 500);
        }
    }

    public function destroy(Restaurant $restaurant, MealDeal $meal) 
    {
        $this->authorize('view', $restaurant);
        $this->authorize('delete', $meal);

        try {
            if (is_null($meal->user_id)) {
                $meal->forceDelete();
            } else {
                $meal->delete();
            }

            return response(null, 204);
        } catch (Exception $ex) {
            Log::error($ex);

            return response(trans('app.general.error'), 500);
        }
    }

    public function reserved(Request $request, MealDeal $meal) 
    {
        $this->authorize('update', $meal);
        
        $request->validate([
            'userId' => 'required|integer|exists:users,id'
        ]);

        try {
            $meal->status = 'reserved';
            $meal->user_id = $request->userId;
            $meal->save();

            return response(null, 204);
        } catch (Exception $ex) {
            Log::error($ex);

            return response(trans('app.general.error'), 500);
        }
    }
}
