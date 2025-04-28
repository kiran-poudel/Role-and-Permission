<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;


class ArticleController extends Controller implements HasMiddleware
{

    public static function middleware()
    {
        return [
            new Middleware('permission:View article', only: ['index']),
            new Middleware('permission:Edit article', only: ['edit']),
            new Middleware('permission:Create article', only: ['create']),
            new Middleware('permission:Delete article', only: ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles = Article::orderBy("created_at", "desc")->paginate(10);
        return view("articles.list", [
            "articles" => $articles
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("articles.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "title" => "required|min:5",
            "author" => "required|min:3",
            "text" => "string|nullable",
        ]);
        if ($validator->passes()) {
            $article = Article::Create([
                "title" => $request->title,
                "text" => $request->text,
                "author" => $request->author,
            ]);


            return redirect()->route("articles.list")->with("success", "Article Added Successfully");
        } else {
            return redirect()->route("articles.create")->withInput()->withErrors($validator);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $article = Article::findOrFail($id);
        return view("articles.edit", [
            "article" => $article
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $article = Article::findOrFail($id);

        $validator = Validator::make($request->all(), [
            "title" => "required|min:5",
            "author" => "required|min:3",
            "text" => "string|nullable",
        ]);
        if ($validator->passes()) {

            $article->title = $request->title;
            $article->text = $request->text;
            $article->author = $request->author;
            $article->save();

            return redirect()->route("articles.list")->with("success", "Article Updated Successfully");
        } else {
            return redirect()->route("articles.edit")->withInput()->withErrors($validator);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $article = Article::findOrFail($id);

        if ($article == null) {
            session()->flash("error", "Article Not Found");
            return response()->json([
                'status' => false

            ]);
        }
        $article->delete();
        session()->flash("success", "Article deleted successfully");
        return redirect()->route('articles.list');
    }
}
