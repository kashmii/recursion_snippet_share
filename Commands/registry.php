<?php
return [
  Commands\Programs\CodeGeneration::class,
  Commands\Programs\DbWipe::class,
  Commands\Programs\GenerateCommands::class,
  Commands\Programs\Migrate::class,
];

// migrate
// `php console migrate`
// `php console migrate --init`
// `php console migrate --rollback`

// db-wipe
// `php console db-wipe`

// サーバーを起動する(例)
// `php -S localhost:8000 index.php`