<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fishing_spots', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('name');
        });

        Schema::table('catch_logs', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('species');
        });

        DB::table('fishing_spots')->whereNull('slug')->get()->each(function ($spot) {
            DB::table('fishing_spots')->where('id', $spot->id)->update([
                'slug' => Str::slug($spot->name . '-' . $spot->id),
            ]);
        });

        DB::table('catch_logs')->whereNull('slug')->get()->each(function ($catch) {
            DB::table('catch_logs')->where('id', $catch->id)->update([
                'slug' => Str::slug($catch->species . '-' . $catch->id),
            ]);
        });

        Schema::table('fishing_spots', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
        });

        Schema::table('catch_logs', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('fishing_spots', function (Blueprint $table) {
            $table->dropColumn('slug');
        });

        Schema::table('catch_logs', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
