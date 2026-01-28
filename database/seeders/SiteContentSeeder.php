<?php

namespace Database\Seeders;

use App\Models\SiteContent;
use Illuminate\Database\Seeder;

class SiteContentSeeder extends Seeder
{
    public function run()
    {
        $contents = [
            [
                'section' => 'home',
                'key' => 'hero_title',
                'value' => 'Expert VAT & Tax Consultant',
                'type' => 'text',
            ],
            [
                'section' => 'home',
                'key' => 'hero_subtitle',
                'value' => 'We provide professional Income Tax, VAT, and Company Affairs solutions in Bangladesh. Secure your financial future with our expert guidance.',
                'type' => 'text',
            ],
            [
                'section' => 'home',
                'key' => 'about_text',
                'value' => "We are a team of experienced tax consultants dedicated to simplifying your financial compliance. With years of expertise in Bangladesh's tax laws, we ensure you pay exactly what you owe and not a penny more.",
                'type' => 'text',
            ],
            [
                'section' => 'home',
                'key' => 'services_title',
                'value' => 'Our Services',
                'type' => 'text',
            ],
            [
                'section' => 'home',
                'key' => 'services_json',
                'value' => json_encode([
                    ['icon' => 'fas fa-file-invoice-dollar', 'title' => 'Income Tax', 'description' => 'Personal and Corporate Income Tax return filing and assessment.'],
                    ['icon' => 'fas fa-percent', 'title' => 'VAT Management', 'description' => 'VAT registration, monthly return, and compliance audit support.'],
                    ['icon' => 'fas fa-search-dollar', 'title' => 'Audit Support', 'description' => 'Prepare financial statements and handle audit queries professionally.'],
                    ['icon' => 'fas fa-building', 'title' => 'Company Affairs', 'description' => 'RJSC registration, trade license, and business incorporation.']
                ]),
                'type' => 'rich_text', // Treating as complex data
            ]
        ];

        foreach ($contents as $content) {
            SiteContent::updateOrCreate(
                ['key' => $content['key']],
                $content
            );
        }
    }
}
