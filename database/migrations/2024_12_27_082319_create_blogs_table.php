<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique(); 
            $table->longText('content');
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('featured_image')->nullable();
            $table->unsignedBigInteger('user_id'); 
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamp('published_at')->nullable(); 
            $table->boolean('is_published')->default(false);

            // Categories and Tags
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null'); 
            $table->unsignedBigInteger('tag_id')->nullable(); 
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('set null'); 

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blogs');
    }
}
