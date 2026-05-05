<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function edit()
    {
        $values = Setting::all()->keyBy('key');
        return view('admin.settings.edit', compact('values'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'site_name'       => ['required', 'string', 'max:120'],
            'site_tagline'    => ['nullable', 'string', 'max:200'],
            'support_email'   => ['required', 'email'],
            'support_phone'   => ['nullable', 'string', 'max:60'],
            'company_name'    => ['nullable', 'string', 'max:120'],
            'company_address' => ['nullable', 'string', 'max:255'],
            'company_taxid'   => ['nullable', 'string', 'max:120'],
            'tax_rate'        => ['nullable', 'integer', 'min:0', 'max:100'],
        ]);

        foreach ($data as $key => $value) {
            $group = in_array($key, ['company_name', 'company_address', 'company_taxid', 'tax_rate']) ? 'invoice' : 'general';
            $type  = $key === 'tax_rate' ? 'integer' : 'string';
            Setting::put($key, $value, $group, $type);
        }

        return back()->with('success', 'Paramètres mis à jour.');
    }
}
