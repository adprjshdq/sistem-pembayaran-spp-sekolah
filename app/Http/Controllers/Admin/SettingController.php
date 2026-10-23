<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    /**
     * @var array<string, string>
     */
    private const KEYS = [
        'school_name' => 'Nama Sekolah',
        'school_address' => 'Alamat Sekolah',
        'school_phone' => 'Telepon Sekolah',
        'school_email' => 'Email Sekolah',
        'treasurer_name' => 'Nama Bendahara',
        'treasurer_nip' => 'NIP Bendahara',
        'headmaster_name' => 'Nama Kepala Sekolah',
        'receipt_note' => 'Catatan Kwitansi',
    ];

    public function edit(): View
    {
        $settings = collect(self::KEYS)
            ->mapWithKeys(fn (string $label, string $key) => [$key => Setting::get($key, '')]);

        return view('admin.settings.edit', [
            'settings' => $settings,
            'labels' => self::KEYS,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'school_name' => ['required', 'string', 'max:150'],
            'school_address' => ['nullable', 'string', 'max:255'],
            'school_phone' => ['nullable', 'string', 'max:60'],
            'school_email' => ['nullable', 'email', 'max:120'],
            'treasurer_name' => ['nullable', 'string', 'max:120'],
            'treasurer_nip' => ['nullable', 'string', 'max:60'],
            'headmaster_name' => ['nullable', 'string', 'max:120'],
            'receipt_note' => ['nullable', 'string', 'max:255'],
        ]);

        foreach (self::KEYS as $key => $label) {
            Setting::set($key, $data[$key] ?? null);
        }

        return redirect()->route('admin.settings.edit')
            ->with('success', 'Pengaturan sekolah berhasil disimpan.');
    }
}
