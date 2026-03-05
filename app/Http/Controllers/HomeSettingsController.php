<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHomeSettingsRequest;
use App\Http\Requests\UpdateHomeSettingsRequest;
use App\Models\HomeSettings;

class HomeSettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(StoreHomeSettingsRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(HomeSettings $homeSettings)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HomeSettings $homeSettings)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHomeSettingsRequest $request, HomeSettings $homeSettings)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HomeSettings $homeSettings)
    {
        //
    }
}
