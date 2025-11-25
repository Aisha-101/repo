<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('rekomendacijos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('knyga_id')->constrained('knygos')->onDelete('cascade');
            $table->string('naudotojas');
            $table->text('komentaras');
            $table->unsignedTinyInteger('ivertinimas'); // 1-5
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('rekomendacijos');
    }
};
