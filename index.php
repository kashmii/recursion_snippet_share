<?php

require_once __DIR__ . '/config.php';

$DEBUG = true;

// recursion のコード
spl_autoload_extensions(file_extensions: ".php");
spl_autoload_register();

// 1. ルートをロードします。
$routes = include('Routing/routes.php');

// 2. リクエストURIを解析してパスだけを取得します。
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// 1と2を組み合わせる (ルートにパスが存在するかチェックします)
if (isset($routes[$path])) {
  // コールバックを呼び出してrendererを作成します。
  $renderer = $routes[$path]();
  setHeadersAndRenderContent($renderer, $DEBUG);
} else if (preg_match("#^" . SHOW_ROUTE_PATTERN . "$#", $path, $matches)) {
  $renderer = $routes[SHOW_ROUTE_PATTERN](...$matches);
  setHeadersAndRenderContent($renderer, $DEBUG);
} else {
  // 一致するルートがない場合、404エラーを表示する
  http_response_code(404);
  echo "404 Not Found: The requested route was not found on this server.";
  printf("<br>debug info:<br>%s<br>%s", json_encode($routes), $path);
}

function setHeadersAndRenderContent($renderer, $DEBUG)
{
  try {
    // ヘッダーを設定
    foreach ($renderer->getFields() as $name => $value) {
      // ヘッダーに対する単純な検証を実行
      $sanitized_value = filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES);

      if ($sanitized_value && $sanitized_value === $value) {
        header("{$name}: {$sanitized_value}");
      } else {
        // ヘッダー設定に失敗した場合、ログに記録するか処理を中断します
        http_response_code(500);
        if ($DEBUG) print("Failed setting header - original: '$value', sanitized: '$sanitized_value'");
        exit;
      }
    }

    // コンテンツを出力
    print($renderer->getContent());
  } catch (Exception $e) {
    http_response_code(500);
    print("Internal error, please contact the admin.<br>");
    if ($DEBUG) print($e->getMessage());
  }
}
