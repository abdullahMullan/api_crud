<?php

namespace App\Http\Controllers\API;

use App\Models\post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class Postcontroller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $post = post::all();
        return response()->json([
            'status' => 'true',
            'status' => 'true',
            'data' => $post,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateuser = Validator::make(
            $request->all(),
            [
                'title' => 'required',
                'discription' => 'required',
                'image' => 'required|mimes:png,jpg,jpeg,gif',
            ]
        );
        if ($validateuser->fails()) {
            return response()->json([
                'status' => 'false',
                'message' => 'validationerror',
                'error' => $validateuser->errors()
            ]);
        }
        $img = $request->image;
        $ext = $img->getClientOriginalExtension();
        $imageName = time() . '.' . $ext;
        $img->move(public_path() . '/uploads', $imageName);


        $post = post::create([
            'title' => $request->title,
            'discription' => $request->discription,
            'image' => $imageName,
        ]);
        if ($post) {
            return response()->json([
                'status' => 'true ',
                'message' => 'user created successfully',
                'user' => $post,
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // $post=post::select(
        //     'id',
        //     'title',
        //     'discription',
        //     'image',
        // )->where('id',$id)->get();
        $post = post::where('id', $id)->get();
        return response()->json([
            'status' => 'true ',
            'message' => ' single user obtained successfully',
            'user' => $post,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validateuser = Validator::make(
            $request->all(),
            [
                'title' => 'required',
                'discription' => 'required',
                'image' => 'required|mimes:png,jpg,jpeg,gif',
            ]
        );
        if ($validateuser->fails()) {
            return response()->json([
                'status' => 'false',
                'message' => 'validationerror',
                'error' => $validateuser->errors()
            ]);
        }
        $postimg = post::select(
            'id',
            'image'
        )
            ->where('id', $id)
            ->get();

        if ($request->image != '') {
            $path = public_path() . '/uploads';
            if ($postimg[0]->image != '' && $postimg[0]->image != null) {
                $old_file = $path . $postimg[0]->image;
                if (file_exists($old_file)) {
                    unlink($old_file);
                }
            }
            $img = $request->image;
            $ext = $img->getClientOriginalExtension();
            $imageName = time() . '.' . $ext;
            $img->move(public_path() . '/uploads', $imageName);
        } else {
            $imageName = $postimg->image;
        }





        $post = post::where('id', $id)->update([
            'title' => $request->title,
            'discription' => $request->discription,
            'image' => $imageName,
        ]);
        if ($post) {
            return response()->json([
                'status' => 'true ',
                'message' => 'user updated successfully',
                'user' => $post,
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $image_path = post::select('image')->where('id', $id)->get();
        $file_path = public_path() . '/uploads/' . $image_path[0]['image'];
        unlink($file_path);


        $post = post::where('id', $id)->delete();
        return response()->json([
            'status' => 'true ',
            'message' => 'user deleted successfully',
            'user' => $post,
        ]);
    }
}
