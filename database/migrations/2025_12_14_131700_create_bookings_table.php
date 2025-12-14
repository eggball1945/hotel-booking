<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code', 50)->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
            $table->date('check_in');
            $table->date('check_out');
            $table->unsignedInteger('total_nights')->default(1);
            $table->integer('total_price')->default(0);
            $table->enum('status', ['pending','confirmed','checked_in','checked_out','cancelled'])->default('pending');
            $table->text('special_notes')->nullable();
            $table->string('payment_proof')->nullable();
            $table->dateTime('actual_check_in')->nullable();
            $table->dateTime('actual_check_out')->nullable();
            $table->decimal('additional_charges', 10, 2)->default(0.00);
            $table->decimal('total_paid', 10, 2)->default(0.00);
            $table->enum('payment_status', ['unpaid','partial','paid'])->default('unpaid');
            $table->timestamps();

            $table->index('status');
            $table->index('user_id');
            $table->index('room_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('bookings');
    }
}
