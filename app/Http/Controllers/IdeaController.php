<?php

namespace App\Http\Controllers;

use App\Models\Idea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IdeaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return Idea::with('owner')->with('location')->with('category')->with('images')->with('feedbacks')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'required|string',
            'category_id' => 'required|integer',
            'location_id' => 'required|integer',
            'donation_target' => 'required|integer',
            'user_id' => 'required|exists:App\Models\User,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $idea = Idea::create(array_merge(
            $validator->validated()
        ));

        return response()->json([
            'message' => 'Idea successfully added',
            'user' => $idea
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\idea  $idea
     * @return \Illuminate\Http\Response
     */
    public function show(idea $idea)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\idea  $idea
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, idea $idea)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\idea  $idea
     * @return \Illuminate\Http\Response
     */
    public function destroy(idea $idea)
    {
        //
    }
}
