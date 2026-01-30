<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AccountSettingController extends Controller
{
    public function edit()
    {
        $settings = Setting::first();
        return view('backend.admin.account.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'system_title' => 'required|string|max:255',
            'copyright_text' => 'required|string|max:255',
            'logo' => 'nullable|image|max:2048',
            'favicon' => 'nullable|image|max:2048',
        ]);

        $settings = Setting::firstOrCreate([]);

        if ($request->hasFile('logo')) {
            if ($settings->logo) {
                Storage::disk('public')->delete($settings->logo);
            }
            $data['logo'] = $request->file('logo')->store('settings', 'public');
        }

        if ($request->hasFile('favicon')) {
            if ($settings->favicon) {
                Storage::disk('public')->delete($settings->favicon);
            }
            $data['favicon'] = $request->file('favicon')->store('settings', 'public');
        }

        $settings->update($data);

        // return response()->json([
        //     'success' => 'Settings updated successfully',
        //     'system_title' => $settings->system_title,
        //     'copyright_text' => $settings->copyright_text,
        //     'logo' => $settings->logo ? asset('storage/'.$settings->logo) : null,
        //     'favicon' => $settings->favicon ? asset('storage/'.$settings->favicon) : null,
        // ]);
        return redirect()->back()->with('success', 'Settings updated successfully');
    }
}
