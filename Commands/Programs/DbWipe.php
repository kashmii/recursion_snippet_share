<?php

namespace Commands\Programs;

// 実装アプローチ
// 1. 既存コマンドをコピペして調整
// 2. [done] DBを削除するコマンドを作成
// 3. [done] このコマンドをCommands/registry.phpに追加
// 3-b. [done] このコマンドをCLIで実行できるようにする
// 4. [done] backupを作成するオプションを追加
// 補足: このコマンドは、DBを削除するため、実行する前に確認を求めるようにする

use Commands\AbstractCommand;
use Database\MySQLWrapper;
use Commands\Argument;
use Helpers\Settings;

class DbWipe extends AbstractCommand
{
  // 使用するコマンド名を設定
  protected static ?string $alias = 'db-wipe';

  // 引数を割り当て
  public static function getArguments(): array
  {
    return [
      (new Argument('backup'))
        ->description('Create a backup before deleting the database.')
        ->required(false)
        ->allowAsShort(true),
    ];
  }

  public function execute(): int
  {
    $mysqli = new MySQLWrapper();
    $databaseName = $mysqli->getDatabaseName();
    $username = Settings::env('DATABASE_USER');
    $password = Settings::env('DATABASE_USER_PASSWORD');
    $backupFile = 'backup.sql';

    // backupオプションの処理
    $backup = $this->getArgumentValue('backup');

    if ($backup) {
      $this->log("Creating backup....");

      $command = "mysqldump -u $username -p$password $databaseName > $backupFile";
      exec($command, $output, $returnVar);

      if ($returnVar !== 0) {
        $this->log('Failed to create backup.');
        return 1;
      }

      $this->log('Backup created successfully.');
    }
    // backupオプションの処理 ここまで

    // DBの削除処理
    $query = "DROP DATABASE $databaseName";

    if ($mysqli->query($query) === true) {
      $this->log("Database $databaseName deleted successfully.");
    } else {
      $this->log("Error deleting database: " . $mysqli->error);
      return 1;
    }

    $mysqli->close();

    $this->log('Deleting database.......' . $databaseName);
    return 0;
  }
}
