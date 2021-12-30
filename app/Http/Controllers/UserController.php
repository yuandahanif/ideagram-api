<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Idea;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'show', 'showIdea']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ideas = Idea::with('owner')->with('location')->with('category')->with('images')->get();
        return response()->json([
            'message' => 'get all Idea success',
            'idea' => $ideas
        ], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function idea()
    {
        $ideas = Idea::where('user_id', auth()->user()->id)->with('location')->with('category')->with('images')->orderBy('id', 'DESC')->get();
        return response()->json([
            'message' => 'get all Idea success',
            'idea' => $ideas
        ], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function showIdea(User $user)
    {
        $ideas = Idea::where('user_id', $user->id)->with('location')->with('category')->with('images')->orderBy('id', 'DESC')->get();
        return response()->json([
            'message' => 'get all Idea success',
            'user' => $user,
            'idea' => $ideas
        ], 200);
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
            'idea' => $idea
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
        $idea_info = Idea::where('id', $idea->id)->with('owner')->with('location')->with('category')->with('images')->with('feedbacks')->with('comments')->first();
        $donation_total = 0;
        foreach ($idea_info->feedbacks as $f) {
            $donation = Donation::select()->where('feedback_id', $f->id)->get();
            $donation_total += $donation->sum('amount');
            $f->donation = $donation;
        }
        $idea_info->donation_total = $donation_total;
        return response()->json([
            'message' => 'get Idea success',
            'idea' => $idea_info
        ], 200);
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
            'idea' => $idea
        ], 200);
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

        if ($idea->delete()) {
            return response()->json([
                'message' => 'Idea successfully deleted',
                'idea' => $idea
            ], 200);
        }
        return response()->json([
            'message' => 'Error',
            'user' => $idea
        ], 404);
    }
}
