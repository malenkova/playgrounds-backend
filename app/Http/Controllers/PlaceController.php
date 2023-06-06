<?php

namespace App\Http\Controllers;

use App\Http\Resources\PlaceCollection;
use App\Http\Resources\PlaceResource;
use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Validator;

class PlaceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(new PlaceCollection(Place::all()), Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'address' => 'required|unique:places,address|max:255'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->messages(), Response::HTTP_BAD_REQUEST);
        }
        $place = Place::create($request->only(['name', 'address', 'googlePlaceId', 'image', 'geometry', 'filter']));
        return response()->json(new PlaceResource($place), Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     */
    public function show(Place $place)
    {
        return response()->json(new PlaceResource($place), Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Place $place)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'address' => 'required|unique:places,address|max:255'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->messages(), Response::HTTP_BAD_REQUEST);
        }
        $place->update($request->only(['name', 'address', 'googlePlaceId', 'image', 'geometry', 'filter']));
        return response()->json(new PlaceResource($place), Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Place $place)
    {
        $place->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
