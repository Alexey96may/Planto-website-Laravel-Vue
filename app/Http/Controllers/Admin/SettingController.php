<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Inertia\Inertia;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->all();

        return Inertia::render('Admin/Settings/Index', [
            'settings' => $settings
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'site_name' => 'required|string',
            'contact_phone' => 'nullable|string',
            'contact_email' => 'nullable|email',
            'is_open' => 'required|boolean',
            'link_fb' => 'nullable|url',
            'link_x' => 'nullable|url',
            'link_li' => 'nullable|url',
            'footer_main_text'  => 'nullable|string|max:1000',
            'footer_rights'     => 'nullable|string|max:255',
            'products_per_page' => 'required|integer|min:1|max:100',

            'slider_main_limit'     => 'required|integer|min:1|max:10',
            'slider_features_limit' => 'required|integer|min:1|max:6',
            'live_demo_url'         => 'nullable|url',

            'section_trendy_title'  => 'required|string|max:100',
            'section_selling_title' => 'required|string|max:100',
            'section_reviews_title' => 'required|string|max:100',
            'section_o2_title'      => 'required|string|max:100',

            'section_trendy_link'   => 'nullable|string|max:255',
            'section_selling_link'  => 'nullable|string|max:255',
            'section_reviews_link'  => 'nullable|string|max:255',
            'section_o2_link'       => 'nullable|string|max:255',

            'top_plants_limit'   => 'required|integer|min:1|max:100',
            'comments_limit'     => 'required|integer|min:1|max:100',
            'features_limit'     => 'required|integer|min:1|max:100',
            'reviews_limit'      => 'required|integer|min:1|max:100',
            'trendy_limit'       => 'required|integer|min:1|max:100',

            'contact_address'     => 'nullable|string|max:255',
        ]);

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return back()->with('success', 'Settings are succesfully updated!');
    }
}
