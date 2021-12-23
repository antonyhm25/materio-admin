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
use App\Http\Resources\Users\UserPaginated;
use App\Http\Resources\UserConsumers\UserConsumerResult;

class UserConsumersController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users|email',
            'password' => 'required|min:6|alpha_num',
            'repeatPassword' => 'required|same:password',
            'enable' => 'required|in:0,1',
            'restaurant' => 'required|array:name,address'
        ]);
    
        try {
            DB::beginTransaction();

            $user = new User($request->all());
            $user->password = bcrypt($request->password);
            $user->type = 'local';
            $user->save();
            
            $user->restaurant()->create([
                'name' => $request->restaurant['name'],
                'address' => $request->restaurant['address']
            ]);
            
            $user->assignRole(RolesType::LOCAL);

            DB::commit();

            return new UserConsumerResult($user);
        } catch (Exception $ex) {
            Log::error($ex);
            DB::rollBack();

            return response(trans('app.general.error'), 500);
        }
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
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
        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png,svg,gif|max:2048',
        ]);

        try {
            $photo = $request->photo;
            $fileName = Str::random() .".". $photo->getClientOriginalExtension();
            
            $filePath = storage_path('app/public/thumbnails');

            $img = Image::make($photo->path());
            $img->resize(400, 400, function ($const) {
                $const->aspectRatio();
            })
            ->save("$filePath/$fileName");

            $restaurant->photo = "thumbnails/$fileName";
            $restaurant->save();

            return response()->json([
                'path' => "thumbnails/$fileName"
            ]);

        } catch (Exception $ex) {
            Log::error($ex);

            return response(trans('app.general.error'), 500);
        }
    }
}
