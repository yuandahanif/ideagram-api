<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\File;
use App\Models\Idea;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class IdeaController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ideas = Idea::with('owner')->with('location')->with('category')->with('images')->orderBy('id', 'DESC')->get();
        return response()->json([
            'message' => 'get all Idea success',
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function image(Request $request, Idea $idea)
    {
        $validator = Validator::make($request->all(), [
            'photos' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        if ($request->hasFile('photos')) {
            foreach ($request->photos as $photo) {
                $ext = $photo->extension();
                if ($ext == 'jpeg' || $ext == 'jpg' || $ext == 'png') {
                    $name = time() . '_' . Str::random(4) . '_' . Str::limit($idea->name, 5, '') . '.' . $ext;
                    // Remove special accented characters - ie. s??.
                    $clean_name = strtr($name, array('??' => 'S', '??' => 'Z', '??' => 's', '??' => 'z', '??' => 'Y', '??' => 'A', '??' => 'A', '??' => 'A', '??' => 'A', '??' => 'A', '??' => 'A', '??' => 'C', '??' => 'E', '??' => 'E', '??' => 'E', '??' => 'E', '??' => 'I', '??' => 'I', '??' => 'I', '??' => 'I', '??' => 'N', '??' => 'O', '??' => 'O', '??' => 'O', '??' => 'O', '??' => 'O', '??' => 'O', '??' => 'U', '??' => 'U', '??' => 'U', '??' => 'U', '??' => 'Y', '??' => 'a', '??' => 'a', '??' => 'a', '??' => 'a', '??' => 'a', '??' => 'a', '??' => 'c', '??' => 'e', '??' => 'e', '??' => 'e', '??' => 'e', '??' => 'i', '??' => 'i', '??' => 'i', '??' => 'i', '??' => 'n', '??' => 'o', '??' => 'o', '??' => 'o', '??' => 'o', '??' => 'o', '??' => 'o', '??' => 'u', '??' => 'u', '??' => 'u', '??' => 'u', '??' => 'y', '??' => 'y'));
                    $clean_name = strtr($clean_name, array('??' => 'TH', '??' => 'th', '??' => 'DH', '??' => 'dh', '??' => 'ss', '??' => 'OE', '??' => 'oe', '??' => 'AE', '??' => 'ae', '??' => 'u'));
                    $clean_name = preg_replace(array('/\s/', '/\.[\.]+/', '/[^\w_\.\-]/'), array('_', '.', ''), $clean_name);

                    $photo->storeAs('images', $clean_name);
                    // dd($path);

                    // $file = new File([
                    //     'name' => $clean_name,
                    //     'url' => url("/file/serve/" . $name)
                    // ]);

                    $idea->images()->create([
                        'name' => $clean_name,
                        'url' => url("/file/serve/" . $clean_name)
                    ]);
                }
            }
        }

        return response()->json([
            'message' => 'Idea successfully added',
            'idea' => $idea
        ], 201);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteImage(Request $request, Idea $idea)
    {
        // TODO: del
        $validator = Validator::make($request->all(), [
            'photos' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        if ($request->hasFile('photos')) {
            foreach ($request->photos as $photo) {
                $ext = $photo->extension();
                if ($ext == 'jpeg' || $ext == 'jpg' || $ext == 'png') {
                    $name = time() . '_' . Str::random(4) . '_' . Str::limit($idea->name, 5, '') . '.' . $ext;
                    // Remove special accented characters - ie. s??.
                    $clean_name = strtr($name, array('??' => 'S', '??' => 'Z', '??' => 's', '??' => 'z', '??' => 'Y', '??' => 'A', '??' => 'A', '??' => 'A', '??' => 'A', '??' => 'A', '??' => 'A', '??' => 'C', '??' => 'E', '??' => 'E', '??' => 'E', '??' => 'E', '??' => 'I', '??' => 'I', '??' => 'I', '??' => 'I', '??' => 'N', '??' => 'O', '??' => 'O', '??' => 'O', '??' => 'O', '??' => 'O', '??' => 'O', '??' => 'U', '??' => 'U', '??' => 'U', '??' => 'U', '??' => 'Y', '??' => 'a', '??' => 'a', '??' => 'a', '??' => 'a', '??' => 'a', '??' => 'a', '??' => 'c', '??' => 'e', '??' => 'e', '??' => 'e', '??' => 'e', '??' => 'i', '??' => 'i', '??' => 'i', '??' => 'i', '??' => 'n', '??' => 'o', '??' => 'o', '??' => 'o', '??' => 'o', '??' => 'o', '??' => 'o', '??' => 'u', '??' => 'u', '??' => 'u', '??' => 'u', '??' => 'y', '??' => 'y'));
                    $clean_name = strtr($clean_name, array('??' => 'TH', '??' => 'th', '??' => 'DH', '??' => 'dh', '??' => 'ss', '??' => 'OE', '??' => 'oe', '??' => 'AE', '??' => 'ae', '??' => 'u'));
                    $clean_name = preg_replace(array('/\s/', '/\.[\.]+/', '/[^\w_\.\-]/'), array('_', '.', ''), $clean_name);

                    $photo->storeAs('images', $clean_name);
                    // dd($path);

                    // $file = new File([
                    //     'name' => $clean_name,
                    //     'url' => url("/file/serve/" . $name)
                    // ]);

                    $idea->images()->create([
                        'name' => $clean_name,
                        'url' => url("/file/serve/" . $name)
                    ]);
                }
            }
        }

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
        $idea_info = Idea::where('id', $idea->id)->with('owner')->with('location')->with('category')->with('images')->with('feedbacks')->first();
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
            'idea' => $idea
        ], 404);
    }
}
