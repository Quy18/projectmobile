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
        Schema::create('chatbot_responses', function (Blueprint $table) {
            $table->id();
            $table->string('keyword', 100)->comment('Từ khóa để nhận diện câu hỏi');
            $table->text('response')->comment('Câu trả lời cho từ khóa');
            $table->tinyInteger('priority')->default(0)->comment('Độ ưu tiên của câu trả lời, cao hơn sẽ được chọn trước');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chatbot_responses');
    }
}; 