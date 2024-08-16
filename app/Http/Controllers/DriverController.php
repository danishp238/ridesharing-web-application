<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();

        // takes a relationship as a string and determines if there is a driver relationship available on the model
        $user->load('driver');

        return $request->user();

    }


    public function update(Request $request)
    {
        $request->validate([

            'year' => 'required|numeric|between:2008,2024',
            'make' => 'required',
            'model' => 'required',
            'color' => 'required|alpha',
            'license_plate' => 'required'
        ]);   

        $user = $request->user();

        $user->update($request->only('name'));

        // create or update a driver assoicated witht this user

        // if we access the driver as a method we will have access to eloquent builder methods

        $user->driver()->updateOrCreate($request->only([
            'year',
            'make',
            'model',
            'color',
            'license_plate'
        ]));

        $user->load('driver');

        return $user;
    }
}
