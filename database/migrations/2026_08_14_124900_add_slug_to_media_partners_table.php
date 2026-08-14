<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('media_partners', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name');
        });

        // Backfill slug for any existing rows before enforcing uniqueness.
        DB::table('media_partners')->orderBy('id')->get(['id', 'name'])->each(function ($row) {
            $base = Str::slug($row->name) ?: 'partner';
            $slug = $base;
            $suffix = 1;
            while (DB::table('media_partners')->where('slug', $slug)->where('id', '!=', $row->id)->exists()) {
                $slug = $base . '-' . (++$suffix);
            }
            DB::table('media_partners')->where('id', $row->id)->update(['slug' => $slug]);
        });

        Schema::table('media_partners', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->unique()->change();
        });
    }

    public function down(): void
    {
        Schema::table('media_partners', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
