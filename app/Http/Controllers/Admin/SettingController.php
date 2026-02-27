<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        return view('admin.settings.index', [
            'siteName' => Setting::get('site_name', config('app.name')),
            'siteLogo' => Setting::get('site_logo'),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'site_name' => ['required','string','max:255'],
            'site_logo' => ['nullable','image','max:2048'],
            'remove_logo' => ['nullable','boolean'],
        ]);

        Setting::set('site_name', $data['site_name']);

        $currentLogo = Setting::get('site_logo');
        if ($request->boolean('remove_logo') && $currentLogo) {
            Storage::disk('public')->delete($currentLogo);
            Setting::set('site_logo', null);
        }

        if ($request->hasFile('site_logo')) {
            if ($currentLogo) {
                Storage::disk('public')->delete($currentLogo);
            }
            $path = $request->file('site_logo')->store('site', 'public');
            Setting::set('site_logo', $path);
        }

        return redirect()->route('admin.settings.index')->with('success', 'Site settings updated');
    }
}
