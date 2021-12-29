<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DonationController extends Controller
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
        $donations = User::where('id', auth()->user()->id)->with('donations')->with('donations.feedback')->with('donations.feedback.idea')->first();

        return response()->json([
            'message' => 'get all donation for current user success',
            'user' => $donations
            // 'donations' => auth()->user()
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
            'feedback_id' => 'required|exists:App\Models\Feedback,id',
            'amount' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::where('id', auth()->user()->id)->first();

        $donation = Donation::create(array_merge(
            $validator->validated(),
            ['user_id' => $user->id]
        ));

        return response()->json([
            'message' => 'donation successfully created',
            'donation' => $donation
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\donation  $donation
     * @return \Illuminate\Http\Response
     */
    public function show(donation $donation)
    {
        $donation_ = Donation::where('id', $donation->id)->with('feedback')->with('feedback.idea')->first();
        return response()->json([
            'message' => 'get donation success',
            'donation' => $donation_
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\donation  $donation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, donation $donation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\donation  $donation
     * @return \Illuminate\Http\Response
     */
    public function destroy(donation $donation)
    {
        //
    }
}
