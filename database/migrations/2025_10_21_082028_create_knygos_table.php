
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('knygos', function (Blueprint $table) {
            $table->id();
            $table->string('pavadinimas');
            $table->string('autorius');
            $table->text('aprasymas')->nullable();
            $table->string('isbn')->unique();
            $table->foreignId('kategorija_id')->constrained('kategorijas')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('knygos');
    }
};
