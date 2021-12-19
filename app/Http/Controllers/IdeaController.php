<?php

namespace App\Http\Controllers;

use App\Models\Donation;
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
        $ideas = Idea::with('owner')->with('location')->with('comments')->with('category')->with('images')->with('feedbacks')->get();
        return $ideas;
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
            'category_id' => 'required|exists:App\Models\Category,id',
            'location_id' => 'required|exists:App\Models\Location,id',
            'due_date' => 'required|date',
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
    public function show(Idea $idea)
    {
        // $idea->with('owner')->with('location')->with('comments')->with('category')->with('images')->with('feedbacks');
        $idea_info = Idea::find($idea->id)->with('owner')->with('location')->with('category')->with('images')->with('feedbacks')->with('comments')->first();
        $donation_total = 0;
        foreach ($idea_info->feedbacks as $f) {
            $donation = Donation::select()->where('feedback_id', $f->id)->get();
            $donation_total += $donation->sum('amount');
            $f->donation = $donation;
        }
        $idea_info->donation_total = $donation_total;
        // return $idea_info->feedbacks;
        return $idea_info;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\idea  $idea
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Idea $idea)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'required|string',
            'category_id' => 'required|exists:App\Models\Category,id',
            'location_id' => 'required|exists:App\Models\Location,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $idea->name = $request->name;
        $idea->description = $request->description;
        $idea->category_id = $request->category_id;
        $idea->location_id = $request->location_id;

        if ($idea->isDirty()) {
            $idea->save();
        }

        return response()->json([
            'message' => 'Idea successfully updated',
            'user' => $idea
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\idea  $idea
     * @return \Illuminate\Http\Response
     */
    public function destroy(Idea $idea)
    {
        $idea->comments()->delete();
        // $idea->feedbacks()->delete();
        $idea->images()->delete();
        return $idea->delete();
    }
}
