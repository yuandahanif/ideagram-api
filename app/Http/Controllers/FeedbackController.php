<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FeedbackController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return 'not implemented';
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
            'donation_min' => 'required|integer',
            'idea_id' => 'required|exists:App\Models\Idea,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $feedback = Feedback::create(array_merge(
            $validator->validated()
        ));

        return response()->json([
            'message' => 'Feedback successfully added',
            'feedback' => $feedback
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Feedback  $feedback
     * @return \Illuminate\Http\Response
     */
    public function show(Feedback $feedback)
    {
        $feedback_ =  Feedback::where('id', $feedback->id)->with('idea')->first();
        return response()->json([
            'message' => 'Feedback successfully added',
            'feedback' => $feedback_
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Feedback  $feedback
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Feedback $feedback)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'required|string',
            'donation_min' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $feedback->name = $request->name;

        if ($feedback->isDirty()) {
            $feedback->save();
        }

        return response()->json([
            'message' => 'Feedback successfully updated',
            'feedback' => $feedback
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Feedback  $feedback
     * @return \Illuminate\Http\Response
     */
    public function destroy(Feedback $feedback)
    {
        if ($feedback->delete()) {
            return response()->json([
                'message' => 'Feedback successfully deleted',
                'feedback' => $feedback
            ], 200);
        }
        return response()->json([
            'message' => 'delete feedback error',
            'feedback' => $feedback
        ], 404);
    }
}
