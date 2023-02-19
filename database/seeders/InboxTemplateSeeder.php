<?php

namespace Database\Seeders;

use App\Models\InboxTemplate;
use Illuminate\Database\Seeder;

class InboxTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        InboxTemplate::firstOrCreate([
            'name' => 'gmail',
            'imap_host' => 'imap.gmail.com',
            'imap_port' => 993,
            'imap_encryption' => 'ssl',
            'smtp_host' => 'smtp.gmail.com',
            'smtp_port' => 587,
            'smtp_encryption' => 'tls',
        ]);
    }
}
