<?php

namespace Controllers;

use Models\Snippet;
use Database\MySQLWrapper;
use Helpers\ValidationHelper;

session_start();

class SnippetController
{
  public function list()
  {
    $snippets = $this->fetchValidSnippets();
    return $snippets;
  }

  public function show($token)
  {
    // Snippet詳細のデータ取得処理（モデルからデータを取得）
    require '../views/detail.php';
  }

  public function submit()
  {
    try {
      if (empty($_POST['title']) || empty($_POST['body']) || empty($_POST['language'])) {
        throw new \InvalidArgumentException("Error: Required fields are missing.");
      }

      $title = ValidationHelper::stringLength($_POST['title'], 255);
      $body = ValidationHelper::stringLength($_POST['body'], 65535);
      $language = $_POST['language'];

      if (Snippet::validateExpirationInput($_POST['expiration']) === false) {
        throw new \InvalidArgumentException("Error: Invalid expiration input." . $_POST['expiration']);
      }

      $expirationDateTime = $_POST['expiration'] == "" ?
        null : $this->convertExpirationToDateTime($_POST['expiration']);
      $token = $this->generateUniquePath($body);

      $snippet = new Snippet($title, $body, $token, $language, null, $expirationDateTime);
      $success = $this->saveToDatabase($snippet);

      if ($success) {
        $_SESSION['snippet_token'] = $token;
        header('Location: /');
        exit;
      } else {
        throw new \RuntimeException("Error: Failed to save the snippet.");
      }
    } catch (\InvalidArgumentException $e) {
      $error_message = $e->getMessage();
      error_log($error_message);
      echo "Error: " . $error_message;
      $_SESSION['error_message'] = $error_message;
      header('Location: /');
      exit;
    } catch (\RuntimeException $e) {
      // その他の例外が発生した場合の処理
      error_log($e->getMessage());
      echo "Error: " . $e->getMessage();
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

  private function fetchValidSnippets()
  {
    $db = new MySQLWrapper();
    $snippets = $db->prepareAndFetchAll(
      "SELECT * FROM snippets WHERE expiration > NOW() ORDER BY created_at DESC LIMIT 100",
      '',
      []
    );
    $db->close();
    return $snippets;
  }
}
