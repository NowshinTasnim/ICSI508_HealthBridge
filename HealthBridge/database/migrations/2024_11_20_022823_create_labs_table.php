<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class CreateLabTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $createlabtab = "CREATE TABLE IF NOT EXISTS Lab(
            LabID int NOT null AUTO_INCREMENT,
            Lab_Name varchar(255) not null,
            Physical_address varchar(255) not null,
            License_no varchar(100) not null,
            Phone_no varchar(10),
            Email varchar(50) not null,
            CONSTRAINT phone_no_chek CHECK (LENGTHB(Phone_no) = 10),
            CONSTRAINT email_chk CHECK (Email like '%_@__%.__%'),
            CredentialID int,
            PRIMARY KEY(LabID),
            FOREIGN KEY(CredentialID) REFERENCES Credentials(CredentialID) ON DELETE CASCADE
        )
        COLLATE = 'utf8mb4_unicode_ci'
        ENGINE = InnoDB
        AUTO_INCREMENT = 1;
        ";

        DB::statement($createlabtab);

        // Schema::create('labs', function (Blueprint $table) {
        //     $table->id();
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::dropIfExists('labs');
    }
};
