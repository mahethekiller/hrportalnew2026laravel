<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\File;

class ThemeService
{
    protected string $settingsFilePath;

    public function __construct()
    {
        $this->settingsFilePath = storage_path('app/settings/theme_system_config.json');
    }

    /**
     * Get built-in color profiles map.
     */
    public function getColorProfiles(): array
    {
        return [
            'fern' => [
                'name' => 'Fern Emerald',
                'primary_light' => '#2F7A63',
                'primary_dark' => '#4FA98C',
                'primary_active' => '#24624F',
                'primary_wash_light' => '#E4F0EA',
                'primary_wash_dark' => '#1B3A33',
                'rgb' => '47, 122, 99',
                'icon' => 'fa-leaf',
                'color' => '#2F7A63',
            ],
            'sapphire' => [
                'name' => 'Sapphire Corporate',
                'primary_light' => '#2563EB',
                'primary_dark' => '#60A5FA',
                'primary_active' => '#1D4ED8',
                'primary_wash_light' => '#EFF6FF',
                'primary_wash_dark' => '#1E3A8A',
                'rgb' => '37, 99, 235',
                'icon' => 'fa-gem',
                'color' => '#2563EB',
            ],
            'violet' => [
                'name' => 'Royal Violet',
                'primary_light' => '#7C3AED',
                'primary_dark' => '#A78BFA',
                'primary_active' => '#6D28D9',
                'primary_wash_light' => '#F5F3FF',
                'primary_wash_dark' => '#3B0764',
                'rgb' => '124, 58, 237',
                'icon' => 'fa-crown',
                'color' => '#7C3AED',
            ],
            'ruby' => [
                'name' => 'Ruby Crimson',
                'primary_light' => '#DC2626',
                'primary_dark' => '#F87171',
                'primary_active' => '#B91C1C',
                'primary_wash_light' => '#FEF2F2',
                'primary_wash_dark' => '#450A0A',
                'rgb' => '220, 38, 38',
                'icon' => 'fa-fire',
                'color' => '#DC2626',
            ],
            'cyan' => [
                'name' => 'Ocean Cyan',
                'primary_light' => '#0891B2',
                'primary_dark' => '#22D3EE',
                'primary_active' => '#0E7490',
                'primary_wash_light' => '#ECFEFF',
                'primary_wash_dark' => '#164E63',
                'rgb' => '8, 145, 178',
                'icon' => 'fa-water',
                'color' => '#0891B2',
            ],
            'amber' => [
                'name' => 'Sunset Amber',
                'primary_light' => '#D97706',
                'primary_dark' => '#FBBF24',
                'primary_active' => '#B45309',
                'primary_wash_light' => '#FFFBEB',
                'primary_wash_dark' => '#451A03',
                'rgb' => '217, 119, 6',
                'icon' => 'fa-sun',
                'color' => '#D97706',
            ],
            'obsidian' => [
                'name' => 'Midnight Obsidian',
                'primary_light' => '#1E293B',
                'primary_dark' => '#94A3B8',
                'primary_active' => '#0F172A',
                'primary_wash_light' => '#F8FAFC',
                'primary_wash_dark' => '#020617',
                'rgb' => '30, 41, 59',
                'icon' => 'fa-moon',
                'color' => '#1E293B',
            ],
        ];
    }

    /**
     * Get available Font Families.
     */
    public function getFontFamilies(): array
    {
        return [
            'inter' => [
                'name' => 'Inter / Executive Clean',
                'family' => '-apple-system, BlinkMacSystemFont, "Inter", "Segoe UI", Roboto, sans-serif',
            ],
            'outfit' => [
                'name' => 'Outfit / Geometric Premium',
                'family' => '"Outfit", -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif',
            ],
            'roboto' => [
                'name' => 'Roboto / Precision Technical',
                'family' => '"Roboto", -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif',
            ],
            'plus_jakarta' => [
                'name' => 'Plus Jakarta Sans / Friendly Modern',
                'family' => '"Plus Jakarta Sans", -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif',
            ],
        ];
    }

    /**
     * Get available Seasonal Accents.
     */
    public function getSeasonalAccents(): array
    {
        return [
            'off' => [
                'name' => 'Disabled (Standard Corporate)',
                'badge' => 'Off',
                'banner_css' => '',
                'text' => '',
                'icon' => 'fa-slash',
            ],
            'diwali' => [
                'name' => 'Diwali Festival Gold',
                'badge' => 'Diwali Gold',
                'banner_css' => 'background: linear-gradient(90deg, #B45309 0%, #F59E0B 50%, #B45309 100%); color: #ffffff;',
                'text' => '🪔 Happy Diwali! Wishing you prosperity, happiness, and success.',
                'icon' => 'fa-om',
            ],
            'national' => [
                'name' => 'Independence & Republic Day',
                'badge' => 'Freedom Tricolor',
                'banner_css' => 'background: linear-gradient(90deg, #FF671F 0%, #FFFFFF 50%, #046A38 100%); color: #1e293b;',
                'text' => '🇮🇳 Celebrating Pride, Unity, and Progress.',
                'icon' => 'fa-flag',
            ],
            'newyear' => [
                'name' => 'New Year Celebration',
                'badge' => 'New Year',
                'banner_css' => 'background: linear-gradient(90deg, #4C1D95 0%, #C084FC 50%, #1E1B4B 100%); color: #ffffff;',
                'text' => '🎉 Happy New Year! Here is to a brilliant and prosperous year ahead.',
                'icon' => 'fa-champagne-glasses',
            ],
            'holiday' => [
                'name' => 'Festive Holiday Season',
                'badge' => 'Holiday Season',
                'banner_css' => 'background: linear-gradient(90deg, #15803D 0%, #DC2626 50%, #15803D 100%); color: #ffffff;',
                'text' => '🎄 Warmest Season’s Greetings & Festive Wishes from HR.',
                'icon' => 'fa-snowflake',
            ],
        ];
    }

    /**
     * Get System Default Theme Configuration.
     */
    public function getThemeConfig(): array
    {
        if (!File::exists($this->settingsFilePath)) {
            $defaultConfig = [
                'theme_color_profile' => 'fern',
                'custom_primary_hex' => '#2F7A63',
                'theme_mode' => 'light',
                'font_family' => 'inter',
                'sidebar_style' => 'default',
                'border_density' => 'default',
                'seasonal_accent' => 'off',
            ];

            $this->saveThemeConfig($defaultConfig);
            return $defaultConfig;
        }

        $content = File::get($this->settingsFilePath);
        return json_decode($content, true) ?: [];
    }

    /**
     * Save System Default Theme Configuration.
     */
    public function saveThemeConfig(array $config): bool
    {
        $directory = dirname($this->settingsFilePath);
        if (!File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        return File::put($this->settingsFilePath, json_encode($config, JSON_PRETTY_PRINT)) !== false;
    }
}
