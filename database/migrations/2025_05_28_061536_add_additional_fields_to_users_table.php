<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('id');
            $table->string('last_name')->nullable()->after('first_name');
            $table->enum('gender', ['MALE', 'FEMALE', 'OTHER'])->nullable()->after('last_name');
            $table->date('birth_date')->nullable()->after('gender');
            $table->string('marital_status')->nullable()->after('birth_date');
            $table->string('profession')->nullable()->after('marital_status');
            
            $table->string('phone_number')->nullable()->after('email');
            $table->foreignId('country_id')->nullable()->constrained('countries')->nullOnDelete()->after('phone_number');
            $table->string('region')->nullable()->after('country_id');
            $table->string('city')->nullable()->after('region');
            $table->string('postal_code')->nullable()->after('city');
            $table->string('address')->nullable()->after('postal_code');

            $table->string('identity_document_url')->nullable()->after('address');
            $table->string('address_document_url')->nullable()->after('identity_document_url');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'first_name',
                'last_name',
                'gender',
                'birth_date',
                'marital_status',
                'profession',
                'phone_number',
                'country_id',
                'region',
                'city',
                'postal_code',
                'address',
                'identity_document_url',
                'address_document_url',
            ]);
        });
    }
};
