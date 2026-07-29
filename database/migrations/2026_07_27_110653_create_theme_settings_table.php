<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('theme_settings', function (Blueprint $table) {
            $table->id();
            $table->string('fixed_layout');
            $table->string('fixed_footer');
            $table->string('boxed_layout');
            $table->string('page_header');
            $table->string('footer_layout');
            $table->string('statistics_cards');
            $table->string('statistics_cards_background');
            $table->string('employee_cards');
            $table->string('card_border_color');
            $table->string('compact_menu');
            $table->string('flipped_menu');
            $table->string('right_side_icons');
            $table->string('bordered_menu');
            $table->string('form_design');
            $table->integer('is_semi_dark');
            $table->string('semi_dark_color');
            $table->string('top_nav_dark_color');
            $table->string('menu_color_option');
            $table->string('export_orgchart');
            $table->text('export_file_title');
            $table->string('org_chart_layout');
            $table->string('org_chart_zoom');
            $table->string('org_chart_pan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('theme_settings');
    }
};
