<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Meal;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use App\Http\Resources\Meals\MealResult;
use App\Http\Resources\Meals\MealPaginated;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Storage;

class MealsController extends Controller
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
        ]);

        try{
            $size = $request->size ?? 15;
            $sortBy = $request->sortBy ?? 'name';
            $order = $request->order ?? 'asc';
            $search = $request->search ?? null;
            
            $query = Meal::orderBy($sortBy, $order)
                ->search($search)
                ->byRestaurant($restaurant->id)
                ->paginate($size);

            return new MealPaginated($query);
        } catch (Exception $ex){
            Log::error($ex);

            return response(trans('app.general.error'), 500);
        }
    }

    public function show(Restaurant $restaurant,  Meal $meal)
    {
        $this->authorize('view', $restaurant);
        $this->authorize('view', $meal);

        try {
            return new MealResult($meal);
        } catch (Exception $ex) {
            Log::error($ex);

            return response(trans('app.general.error'), 500);
        }
    }

    public function store(Request $request, Restaurant $restaurant)
    {
        $this->authorize('create', $restaurant);

        $request->validate([
            'name' => [
                'required',
                'max:100',
                Rule::unique('meals')
                    ->where('restaurant_id', $request->restaurantId)
                    ->whereNull('deleted_at')
            ],
            'description' => 'nullable|max:500',
            'restaurantId' => 'required|integer|exists:restaurants,id'
        ]);

        try {
            $meal = Meal::create([
                'name' => $request->name,
                'description' => $request->description,
                'restaurant_id' => $request->restaurantId
            ]);

            return new MealResult($meal);
        } catch (Exception $ex) {
            Log::error($ex);

            return response(trans('app.general.error'), 500);
        }
    }

    public function update(Request $request, Restaurant $restaurant, Meal $meal)
    {
        $this->authorize('update', [$restaurant, $meal]);

        $request->validate([
            'name' => [
                'required',
                'max:100',
                Rule::unique('meals')->ignore($meal->id)
                    ->where('restaurant_id', $meal->restaurant_id)
            ],
            'description' => 'nullable|max:500'
        ]);

        try {
            $meal->fill([
                'name' => $request->name,
                'description' => $request->description,
            ]);

            $meal->save();

            return response(null, 204);
        } catch (Exception $ex) {
            Log::error($ex);

            return response(trans('app.general.error'), 500);
        }
    }

    public function destroy(Restaurant $restaurant, Meal $meal) 
    {
        $this->authorize('view', $restaurant);
        $this->authorize('delete', $meal);

        try {
            $meal->delete();

            return response(null, 204);
        } catch (Exception $ex) {
            Log::error($ex);

            return response(trans('app.general.error'), 500);
        }
    }

    public function uploadPhoto(Request $request, Restaurant $restaurant, Meal $meal) 
    {
        $this->authorize('view', $restaurant);
        $this->authorize('update', $meal);

        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png,svg,gif|max:2048',
        ]);

        try {
            $photo = $request->photo;
            $fileName = Str::random() .".". $photo->getClientOriginalExtension();
            
            $filePath = storage_path('app/public/thumbnails/meals');

            $img = Image::make($photo->path());
            $img->resize(400, 400, function ($const) {
                $const->aspectRatio();
            })
            ->save("$filePath/$fileName");

            $lastPath = $meal->photo;

            $meal->photo = "thumbnails/meals/$fileName";
            $meal->save();

            if (!is_null($lastPath) && Storage::exists("public/{$meal->photo}")) {
                Storage::delete("public/{$meal->photo}");
            }

            return response()->json([
                'path' => "thumbnails/meals/$fileName"
            ]);

        } catch (Exception $ex) {
            Log::error($ex);

            return response(trans('app.general.error'), 500);
        }
    }
}
