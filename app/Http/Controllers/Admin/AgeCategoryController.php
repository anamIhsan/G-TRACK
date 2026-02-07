<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AgeCategory;
use Illuminate\Http\Request;

class AgeCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ageCategories = AgeCategory::latest()->paginate(10);

        return view('admin.age_category.index', [
            'ageCategories' => $ageCategories
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.age_category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $requestData = $request->validate([
            'nama' => 'required',
            'range_one' => 'required|integer',
            'range_two' => 'required|integer',
        ]);

        AgeCategory::create($requestData);

        return redirect()->route('admin.age_categories.index');
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
        $data = AgeCategory::find($id);

        return view('admin.age_category.edit', [
            'data' => $data
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $ageCategory = AgeCategory::find($id);

        $requestData = $request->validate([
            'nama' => 'required',
            'range_one' => 'required|integer',
            'range_two' => 'required|integer',
        ]);

        $ageCategory->update($requestData);

        return redirect()->route('admin.age_categories.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ageCategory = AgeCategory::find($id)->delete();

        return redirect()->route('admin.age_categories.index');
    }
}
