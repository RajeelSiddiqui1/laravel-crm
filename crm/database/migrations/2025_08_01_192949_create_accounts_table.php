    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up(): void
        {
            Schema::create('accounts', function (Blueprint $table) {
                $table->id();                       // auto-increment primary key
                $table->string('clientname')->nullable();
                $table->string('phone')->nullable();
                $table->string('email')->nullable();
                $table->string('attachments')->nullable();   // JSON or comma-separated list
                $table->date('due_date')->nullable();
                $table->string('nature_of_business')->nullable();
                $table->enum('priority', ['low', 'high', 'medium'])->default('medium');
                $table->timestamps();               // created_at, updated_at
            });
        }

        public function down(): void
        {
            Schema::dropIfExists('accounts');
        }
    };