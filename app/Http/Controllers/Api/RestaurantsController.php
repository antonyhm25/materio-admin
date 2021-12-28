<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\User;
use App\Helpers\RolesType;
use App\Models\Restaurant;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\Restaurants\RestaurantResult;

class RestaurantsController extends Controller
{
    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $request->validate([
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'required|unique:users|email',
            'password' => 'required|min:6|alpha_num',
            'repeatPassword' => 'required|same:password',
            'enable' => 'required|in:0,1',
            'restaurant' => 'required|array:name,address'
        ]);
    
        try {
            DB::beginTransaction();

            $user = new User([
                'first_name' => $request->firstName,
                'last_name' => $request->lastName,
                'full_name' => "{$request->firstName} {$request->lastName}",
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'enable' => $request->enable,
            ]);

            $user->type = 'admin';
            $user->save();
            
            $user->restaurant()->create([
                'name' => $request->restaurant['name'],
                'address' => $request->restaurant['address']
            ]);
            
            $user->assignRole(RolesType::ADMIN_RESTAURANT);

            DB::commit();

            return new RestaurantResult($user);
        } catch (Exception $ex) {
            Log::error($ex);
            DB::rollBack();

            return response(trans('app.general.error'), 500);
        }
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);
        
        $request->validate([
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => [
                'required',
                Rule::unique('users')->ignore($user->id), 
                'email',
            ], 
            'enable' => 'required|in:0,1',
            'restaurant' => 'required|array:name,address'
        ]);
    
        try {
            $user->fill($request->all());
            $user->save();

            $restaurant = $user->restaurant;
            $restaurant->name = $request->restaurant['name'];
            $restaurant->address = $request->restaurant['address'];
            $restaurant->save();

            return response(null, 204);
        } catch (Exception $ex) {
            Log::error($ex);

            return response(trans('app.general.error'), 500);
        }
    }

    public function uploadPhoto(Request $request, Restaurant $restaurant) 
    {
        $this->authorize('update', $restaurant->user);
        
        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png,svg,gif|max:2048',
        ]);

        try {
            $photo = $request->photo;
            $fileName = Str::random() .".". $photo->getClientOriginalExtension();
            
            $filePath = storage_path('app/public/thumbnails/restaurants');

            $img = Image::make($photo->path());
            $img->resize(400, 400, function ($const) {
                $const->aspectRatio();
            })
            ->save("$filePath/$fileName");
            
            $lastPath = $restaurant->photo;
           
            $restaurant->photo = "thumbnails/restaurants/$fileName";
            $restaurant->save();

            if (!is_null($lastPath) && Storage::exists("public/{$lastPath}")) {
                Storage::delete("public/{$lastPath}");
            }

            return response()->json([
                'path' => "thumbnails/restaurants/$fileName"
            ]);

        } catch (Exception $ex) {
            Log::error($ex);

            return response(trans('app.general.error'), 500);
        }
    }
}
