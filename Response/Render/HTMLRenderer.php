<?php

namespace Response\Render;

use Response\HTTPRenderer;

class HTMLRenderer implements HTTPRenderer
{
  private string $viewFile;
  private array $data;

  public function __construct(string $viewFile, array $data = [])
  {
    $this->viewFile = $viewFile;
    $this->data = $data;
  }

  public function getFields(): array
  {
    return [
      'Content-Type' => 'text/html; charset=UTF-8',
    ];
  }

  public function getContent(): string
  {
    $viewPath = $this->getViewPath($this->viewFile);

    if (!file_exists($viewPath)) {
      throw new \Exception("View file {$viewPath} does not exist.");
    }

    // ob_start()は出力バッファリングを開始します。
    // これにより、以降の出力はすべてバッファに保存されます。
    ob_start();

    // $this->data 配列のキーを変数として展開します。
    // 例えば、$this->data['title'] が存在する場合、$title という変数が作成されます。
    extract($this->data);

    // 指定されたビュー（テンプレート）ファイルを読み込みます。
    // このファイルの出力はすべてバッファに保存されます。
    require $viewPath;

    // バッファの内容を取得し、バッファをクリアします。
    // 取得した内容にヘッダーとフッターを追加して返します。
    return $this->getHeader() . ob_get_clean();
  }

  private function getHeader(): string
  {
    ob_start();
    require $this->getViewPath('layout/header');
    return ob_get_clean();
  }

  private function getFooter(): string
  {
    ob_start();
    require $this->getViewPath('layout/footer');
    return ob_get_clean();
  }

  private function getViewPath(string $path): string
  {
    return sprintf("%s/%s/Views/%s.php", __DIR__, '../..', $path);
  }
}
