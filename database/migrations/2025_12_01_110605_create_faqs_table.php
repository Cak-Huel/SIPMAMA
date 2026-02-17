<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('pertanyaan'); // Judul pertanyaan
            $table->text('jawaban');      // Isi jawaban (bisa panjang)
            $table->timestamps();
        });
    }
};
