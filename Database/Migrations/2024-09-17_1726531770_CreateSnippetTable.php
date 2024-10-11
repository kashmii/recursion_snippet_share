<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class CreateSnippetTable implements SchemaMigration
{
    public function up(): array
    {
        return [
            "CREATE TABLE IF NOT EXISTS snippets (
                id INT PRIMARY KEY AUTO_INCREMENT,
                title VARCHAR(255),
                body TEXT,
                token VARCHAR(255),
                language VARCHAR(255),
                expiration DATETIME,
                created_at DATETIME,
                updated_at DATETIME
            );"
        ];
    }

    public function down(): array
    {
        return [
            "DROP TABLE IF EXISTS snippets;"
        ];
    }
}
