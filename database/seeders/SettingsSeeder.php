<?php

namespace Database\Seeders;

use App\Models\Setting; 
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            'site_title'              => 'My Laravel Website',
            'logo_url'                => '/images/logo.png',
            'theme_color'             => '#ff6600',
            'description'             => 'A powerful Laravel application for modern web experiences.',
            'meta_description'        => 'Best platform for blog, tech, and news updates.',
            'author'                  => 'Waqar Ahmad',
            'application_name'        => 'MyLaravelApp',
            'google_site_verification'=> 'google1234567890abc',
            'google_ads_code'         => '<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>',
            'bing_code'               => '<script src="https://cdn.bongacams.com/script.js"></script>',
            'facebook_url'            => 'https://facebook.com/myprofile',
            'twitter_url'             => 'https://twitter.com/myhandle',
            'instagram_url'           => 'https://instagram.com/myhandle',
            'linkedin_url'            => 'https://linkedin.com/in/myhandle',
            'youtube_url'             => 'https://youtube.com/mychannel',
            'tiktok_url'              => 'https://tiktok.com/@myhandle',
        ];

        foreach ($settings as $key => $value) {
            Setting::set($key, $value);
        }
    }
}
