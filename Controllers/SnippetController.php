<?php
// controllers/SnippetController.php

namespace Controllers;

use Models\Snippet;
use Database\MySQLWrapper;

session_start();

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
    if (empty($_POST['title']) || empty($_POST['body']) || empty($_POST['language'])) {
      $_SESSION['error_message'] = "Error: Required fields (title, body or language) are missing.";
      header('Location: /');
      exit;
    }

    if (Snippet::validateExpirationInput($_POST['expiration']) === false) {
      $_SESSION['error_message'] = "Error: Invalid expiration input." . $_POST['expiration'];
      header('Location: /');
      exit;
    }

    $title = $_POST['title'];
    $body = $_POST['body'];
    $language = $_POST['language'];
    $expirationDateTime = $_POST['expiration'] == "" ?
      null : $this->convertExpirationToDateTime($_POST['expiration']);
    $token = $this->generateUniquePath($body);

    $snippet = new Snippet($title, $body, $token, $language, null, $expirationDateTime);
    $success = $this->saveToDatabase($snippet);

    if ($success) {
      $_SESSION['snippet_token'] = $token;

      header('Location: /');
      // exit: セッションデータが正しく保存するために必要だった
      exit;
    } else {
      $_SESSION['error_message'] = "Error: Failed to save the snippet.";
      exit;
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

  private function generateUniquePath($string): string
  {
    $token = hash('md5', $string);
    return $token;
  }

  private function convertExpirationToDateTime($expiration): ?\DateTime
  {
    $datetime = new \DateTime();
    $datetime->modify($expiration);
    return $datetime;
  }
}
