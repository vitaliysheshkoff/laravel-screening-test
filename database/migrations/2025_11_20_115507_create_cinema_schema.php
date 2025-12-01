<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration {
    /*
    ToDo: Create a migration that creates all tables for the following user stories

    For an example on how a UI for an api using this might look like, please try to book a show at https://in.bookmyshow.com/.
    To not introduce additional complexity, please consider only one cinema.

    Please list the tables that you would create including keys, foreign keys and attributes that are required by the user stories.

    ## User Stories

    **Movie exploration**
    * As a user I want to see which films can be watched and at what times
    * As a user I want to only see the shows which are not booked out

    **Show administration**
    * As a cinema owner I want to run different films at different times
    * As a cinema owner I want to run multiple films at the same time in different showrooms

    **Pricing**
    * As a cinema owner I want to get paid differently per show
    * As a cinema owner I want to give different seat types a percentage premium, for example 50 % more for vip seat

    **Seating**
    * As a user I want to book a seat
    * As a user I want to book a vip seat/couple seat/super vip/whatever
    * As a user I want to see which seats are still available
    * As a user I want to know where I'm sitting on my ticket
    * As a cinema owner I don't want to configure the seating for every show
    */

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('duration_minutes');
            $table->timestamps();
        });

        Schema::create('showrooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('seat_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price_multiplier', 5);
            $table->timestamps();
        });

        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('showroom_id')->constrained()->cascadeOnDelete();
            $table->foreignId('seat_type_id')->constrained()->cascadeOnDelete();
            $table->string('row');
            $table->integer('number');
            $table->timestamps();
        });

        Schema::create('shows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_id')->constrained()->cascadeOnDelete();
            $table->foreignId('showroom_id')->constrained()->cascadeOnDelete();
            $table->dateTime('starts_at');
            $table->decimal('base_price');
            $table->timestamps();
        });

        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('show_id')->constrained('shows')->cascadeOnDelete();
            $table->foreignId('seat_id')->constrained('seats')->cascadeOnDelete();
            $table->decimal('price_paid');
            $table->timestamps();

            $table->unique(['show_id', 'seat_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('shows');
        Schema::dropIfExists('seats');
        Schema::dropIfExists('seat_types');
        Schema::dropIfExists('showrooms');
        Schema::dropIfExists('movies');
    }
};
