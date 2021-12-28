<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Idea;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $comments = Comment::where('idea_id', $request->id)->with('user')->orderBy('id', 'DESC')->paginate();

        return response()->json([
            'message' => 'get all comment for idea with id ' . $request->id,
            'comment' => $comments
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
            'comment' => 'required|string',
            'idea_id' => 'required|exists:App\Models\Idea,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::where('id', auth()->user()->id)->first();

        $comment = Comment::create(array_merge(
            $validator->validated(),
            ['user_id' => $user->id]
        ));

        return response()->json([
            'message' => 'comment successfully added',
            'comment' => $comment
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {

        if ($comment->delete()) {
            return response()->json([
                'message' => 'comment successfully deleted',
                'comment' => $comment
            ], 200);
        }
        return response()->json([
            'message' => 'Error',
            'comment' => $comment
        ], 404);
    }
}
