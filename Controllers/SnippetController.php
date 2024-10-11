<?php
// controllers/SnippetController.php

namespace Controllers;

use Models\Snippet;
use Database\MySQLWrapper;

class SnippetController
{
  public function list()
  {
    // Snippet一覧のデータ取得処理（モデルからデータを取得）
    require __DIR__ . '/../Views/list.php';
  }

  public function create()
  {
    // Snippet作成画面の表示処理
    require __DIR__ . '/../views/create.php';
  }

  public function show($token)
  {
    // Snippet詳細のデータ取得処理（モデルからデータを取得）
    require '../views/detail.php';
  }

  public function submit()
  {
    $title = $_POST['title'];
    $body = $_POST['body'];
    // token を作成する関数
    $token = $this->generateUniquePath();
    $language = $_POST['language'];
    $expirationDateTime = isset($_POST['expirationDateTime']) ? new \DateTime($_POST['expirationDateTime']) : null;

    $snippet = new Snippet($title, $body, $token, $language, null, $expirationDateTime);
    $success = $this->saveToDatabase($snippet);

    if ($success) {
      header('Location: /');
    } else {
      echo "Error: Failed to save the snippet.";
    }
  }

  private function saveToDatabase(Snippet $snippet): bool
  {
    $db = new MySQLWrapper();
    try {
      $success = $db->prepareAndExecute(
        "INSERT INTO snippets (title, body, token, language, expiration, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())",
        'sssss',
        [
          $snippet->getTitle(),
          $snippet->getBody(),
          $snippet->getToken(),
          $snippet->getLanguage(),
          $snippet->getExpirationDateTime() ? $snippet->getExpirationDateTime()->format('Y-m-d H:i:s') : null
        ]
      );
    } catch (\Exception $e) {
      // エラーログを記録するか、適切なエラーハンドリングを行う
      error_log($e->getMessage());
      $success = false;
    } finally {
      $db->close();
    }

    return $success;
  }

  private function generateUniquePath(): string
  {
    $token = uniqid('', false);
    return $token;
  }
}
