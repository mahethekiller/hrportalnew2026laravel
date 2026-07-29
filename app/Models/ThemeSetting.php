<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThemeSetting extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'theme_settings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fixed_layout',
        'fixed_footer',
        'boxed_layout',
        'page_header',
        'footer_layout',
        'statistics_cards',
        'statistics_cards_background',
        'employee_cards',
        'card_border_color',
        'compact_menu',
        'flipped_menu',
        'right_side_icons',
        'bordered_menu',
        'form_design',
        'is_semi_dark',
        'semi_dark_color',
        'top_nav_dark_color',
        'menu_color_option',
        'export_orgchart',
        'export_file_title',
        'org_chart_layout',
        'org_chart_zoom',
        'org_chart_pan'
    ];
}
