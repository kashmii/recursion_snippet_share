<?php

$DEBUG = true;

// 以下2行: chatgdpのコード
// index.php が読み込まれると、Routing/routes.php が読み込まれ実行される
// require __DIR__ . '/Routing/routes.php';

// recursion のコード
spl_autoload_extensions(file_extensions: ".php");
spl_autoload_register();

// 1. ルートをロードします。
$routes = include('Routing/routes.php');

// 2. リクエストURIを解析してパスだけを取得します。
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$unique_id = uniqid('', false);
echo $unique_id;

// 1と2を組み合わせる (ルートにパスが存在するかチェックします)
// echo $routes[$path];
// echo "<br>";
if (isset($routes[$path])) {
    // コールバックを呼び出してrendererを作成します。
    $renderer = $routes[$path]();

    try {
        // ヘッダーを設定します。
        foreach ($renderer->getFields() as $name => $value) {
            // ヘッダーに対する単純な検証を実行します。
            $sanitized_value = filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES);

            if ($sanitized_value && $sanitized_value === $value) {
                header("{$name}: {$sanitized_value}");
            } else {
                // ヘッダー設定に失敗した場合、ログに記録するか処理します。
                // エラー処理によっては、例外をスローするか、デフォルトのまま続行することもできます。
                http_response_code(500);
                if ($DEBUG) print("Failed setting header - original: '$value', sanitized: '$sanitized_value'");
                exit;
            }

            print($renderer->getContent());
        }
    } catch (Exception $e) {
        http_response_code(500);
        print("Internal error, please contact the admin.<br>");
        if ($DEBUG) print($e->getMessage());
    }
} else {
    // 一致するルートがない場合、404エラーを表示する
    http_response_code(404);
    echo "404 Not Found: The requested route was not found on this server.";
    printf("<br>debug info:<br>%s<br>%s", json_encode($routes), $path);
}
