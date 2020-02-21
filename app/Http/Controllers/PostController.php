<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;

class PostController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function uploadImage(Request $request)
    {
        if ($request->hasFile('upload')) {
            $file = $request->file('upload');
            $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $fileName = $fileName . '_' . time() . '.' . $file->getClientOriginalExtension();
        
            $file->move(public_path('uploads'), $fileName);
   
            $ckeditor = $request->input('CKEditorFuncNum');
            $url = asset('uploads/' . $fileName); 
            $msg = 'Image uploaded successfully'; 
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($ckeditor, '$url', '$msg')</script>";
               
            @header('Content-type: text/html; charset=utf-8'); 
            return $response;
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string',
            'content' => 'required'
        ]);

        Post::create([
            'title' => $request->title,
            'content' => $request->content
        ]);
        return redirect()->back()->with(['success' => 'Artikel disimpan']);
    }
}
