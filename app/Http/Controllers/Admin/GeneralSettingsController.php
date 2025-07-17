<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GeneralSettingsController extends Controller
{
    public function index()
    {
        $generalSettings = GeneralSetting::first();

        return view('admin.pages.settings.general_settings', compact('generalSettings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
            'max_stock_per_user' => 'required|numeric|min:1',
            'withdraw_shopping_wallet_percentage' => 'required|numeric|min:0|max:100',
            'withdraw_charge' => 'required|numeric|min:0|max:100',
        ]);

        $generalSettings = GeneralSetting::first();

        if (!$generalSettings) {
            $generalSettings = GeneralSetting::create([
                'app_name' => $request->app_name,
                'max_stock_per_user' => $request->max_stock_per_user,
                'withdraw_shopping_wallet_percentage' => $request->withdraw_shopping_wallet_percentage,
                'withdraw_charge' => $request->withdraw_charge,
            ]);
        }

        $data = $request->only([
            'app_name',
            'max_stock_per_user',
            'withdraw_shopping_wallet_percentage',
            'withdraw_charge'
        ]);

        if ($request->hasFile('logo')) {
            if ($generalSettings->logo) {
                Storage::disk('public')->delete($generalSettings->logo);
            }
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        if ($request->hasFile('favicon')) {
            if ($generalSettings->favicon) {
                Storage::disk('public')->delete($generalSettings->favicon);
            }
            $data['favicon'] = $request->file('favicon')->store('favicons', 'public');
        }

        $generalSettings->update($data);

        return redirect()->route('admin.general.settings')->with('success', 'Settings updated successfully!');
    }
}
