Schema::create('stock_checks', function (Blueprint $table) {
    $table->id();
    $table->foreignId('created_by')->constrained('users'); // atau user_id
    // kolom lainnya
    $table->timestamps();
}); 