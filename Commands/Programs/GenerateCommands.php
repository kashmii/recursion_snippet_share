<?php

namespace Commands\Programs;

use Commands\Argument;

class GenerateCommands extends CodeGeneration
{
  protected static ?string $alias = 'generate-commands';
  protected static bool $requiredCommandValue = false;

  public static function getArguments(): array
  {
    return [
      (new Argument('command-name'))
        ->description('The name of the new command to generate.')
        ->required(true)
        ->allowAsShort(true),
    ];
  }

  public function execute(): int
  {
    $commandName = $this->getArgumentValue('command-name');
    $this->generateCommandFile($commandName);
    $this->updateRegistry($commandName);
    return 0;
  }

  // 新しいコマンドファイルを生成する
  // ファイルの中身はひな型が挿入される
  private function generateCommandFile(string $commandName): void
  {
    $template = <<<'EOD'
<?php

namespace Commands\Programs;

use Commands\AbstractCommand;
use Commands\Argument;

class %s extends AbstractCommand
{
  // TODO: エイリアスを手動で設定してください。
  protected static ?string $alias = '{INSERT new-command-alias}';

  // TODO: 引数を設定してください。
  public static function getArguments(): array
  {
    return [];
  }

  // TODO: 実行コードを記述してください。
  public function execute(): int
  {
    return 0;
  }
}
EOD;
    $content = sprintf($template, $commandName);
    $filePath = __DIR__ . "/$commandName.php";
    file_put_contents($filePath, $content);
    $this->log("Generated command file at $filePath");
  }

  private function updateRegistry(string $commandName): void
  {
    $registryPath = __DIR__ . '/../registry.php';
    $registryContent = file_get_contents($registryPath);
    $newEntry = "Commands\\Programs\\$commandName::class,";
    $updatedContent = str_replace('];', "  $newEntry\n];", $registryContent);
    file_put_contents($registryPath, $updatedContent);
    $this->log("Updated registry with $newEntry");
  }
}
