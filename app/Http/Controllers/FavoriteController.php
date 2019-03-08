<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Favorite;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Favorite as FavoriteResource;

class FavoriteController extends Controller
{
    /**
     * Get all elements from the table Favorite
     *
     */
    public function index()
    {
        $favoritedOrNot = Favorite::all();
        return FavoriteResource::collection($favoritedOrNot);
    }


    /**
     * Store new element in Favorite.
     *
     * @param  int  $id
     * @param  string  $link
     */
    public function favoriteImage($id, $link)
    { 
        try{
            $favorite = new Favorite;
            $favorite->email = Auth::user()->email;
            $favorite->image_id = $id;
            $favorite->imgurl = $link;
            if($favorite->save()){
                Auth::user()->increment('counter',1);
                return new FavoriteResource($favorite);
            }
        }
        catch(\Exception $e){
            echo $e->getMessage();
        }
    }

    /**
     * Get favorited images by current auth user.
     *
     * @param  /
     */
    public function seek()
    {
        if (Auth::user()){
            $favoritedOrNot = Favorite::where('email', Auth::user()->email)->get();
            return FavoriteResource::collection($favoritedOrNot);
        }
    }

    /**
     * Delete element by id from Favorite table for the auth user
     *
     * @param  int  $id
     */
    public function unfavoriteImage($id)
    {
        if (Auth::user()){
            $favToRomve = Favorite::where('email', Auth::user()->email)->where('image_id', $id);
            if ($favToRomve->delete()) {
                Auth::user()->decrement('counter',1);
                return response()->json('Deleted');
            }
        }
    }
}
