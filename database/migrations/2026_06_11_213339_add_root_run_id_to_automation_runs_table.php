<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('automation_runs', function (Blueprint $table) {
            // Links every run forked by a fan-out back to the run that started the
            // execution, so the editor test panel can aggregate all branches of a
            // single test under one root. Null on the root run itself.
            $table->foreignUuid('root_run_id')->nullable()->after('automation_id')
                ->constrained('automation_runs')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('automation_runs', function (Blueprint $table) {
            $table->dropConstrainedForeignId('root_run_id');
        });
    }
};
