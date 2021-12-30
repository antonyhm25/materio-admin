<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Meal;
use App\Models\MealDeal;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class DashboardsController extends Controller
{
    public function restaurantMeals(Request $request, Restaurant $restaurant) 
    {
        $this->authorize('view', $restaurant);

        try {
            $totalMeasl = Meal::where('restaurant_id', $restaurant->id)->count();

            $mealDeasls = MealDeal::select(
                'meal_deals.status',
                DB::raw('COUNT(*) as total')
            )
            ->join('meals', 'meals.id', '=', 'meal_deals.meal_id')
            ->where('meals.restaurant_id', $restaurant->id)
            ->groupBy('meal_deals.status')
            ->get();

            $data = [
                'meals' => $totalMeasl,
                'types' => $mealDeasls
            ];

            return response()->json($data);
        } catch (Exception $ex) {
            Log::error($ex);

            return response(trans('app.general.error'), 500);
        }
    }
}
