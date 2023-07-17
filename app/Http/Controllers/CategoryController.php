<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Category::all();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */


    public function get_items()
    {
        $categories = Category::all();
        $data = [];

        foreach ($categories as $category) {
            $json_category = new Category;
            $json_category->id = $category['id'];
            $json_category->name = $category['name'];
            $json_category->items = $category['items'];

            $data[] = $json_category;
        }
        return $data;
    }

    public function store(Request $request)
    {
        $category = new Category;
        $category->name = $request->input('name');
        $category->save();
    }

    /**
     * Display the specified resource.
     */


    public function show($id)
    {
        return Category::findOrFail($id);
    }



    public function find_by_name(Request $request)
    {
        return Category::where('name', '=', $request->input('name'))->first();
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $category = Category::findOrFail($request->input('id'));
        $category->name = $request->input('name');

        $category->save();

        return $category;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
    }
}
