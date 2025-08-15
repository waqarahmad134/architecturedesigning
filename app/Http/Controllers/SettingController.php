<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required|string|unique:settings,key',
            'value' => 'nullable|string',
        ]);

        Setting::create([
            'key' => $request->key,
            'value' => $request->value,
        ]);

        return back()->with('success', 'New setting added successfully.');
    }

    public function update(Request $request)
    {
        foreach ($request->input('settings', []) as $key => $value) {
            Setting::set($key, $value);
        }

        return back()->with('success', 'Settings updated successfully.');
    }
}
