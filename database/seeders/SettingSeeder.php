<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            ['key' => 'site_name',       'value' => 'EbookSaaS',                          'group' => 'general'],
            ['key' => 'site_tagline',    'value' => 'La bibliothèque numérique premium', 'group' => 'general'],
            ['key' => 'support_email',   'value' => 'contact@ebooksaas.test',             'group' => 'general'],
            ['key' => 'support_phone',   'value' => '+225 27 22 00 00 00',                'group' => 'general'],
            ['key' => 'company_name',    'value' => 'EbookSaaS SARL',                     'group' => 'invoice'],
            ['key' => 'company_address', 'value' => 'Cocody Riviera, Abidjan',            'group' => 'invoice'],
            ['key' => 'company_taxid',   'value' => 'CC-1234567 / RCCM CI-ABJ-2024-X-12345', 'group' => 'invoice'],
            ['key' => 'tax_rate',        'value' => '0',                                  'group' => 'invoice', 'type' => 'integer'],
        ];

        foreach ($defaults as $row) {
            Setting::updateOrCreate(
                ['key' => $row['key']],
                ['value' => $row['value'], 'group' => $row['group'], 'type' => $row['type'] ?? 'string']
            );
        }
    }
}
