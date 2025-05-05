<?php

// database/migrations/2025_05_05_000000_create_products_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();  // Automatically creates an 'id' column as the primary key
            $table->string('name');  // Name of the product
            $table->text('description');  // Description of the product
            $table->decimal('price', 10, 2);  // Price with two decimal places
            $table->integer('stock');  // Stock quantity
            $table->timestamps();  // Created and updated timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
