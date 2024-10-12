<?php

namespace Models;

use DateTime;
use Models\Interfaces\Model;
use Models\Traits\GenericModel;

class Snippet implements Model
{
  use GenericModel;

  // php 8のコンストラクタのプロパティプロモーションは、インスタンス変数を自動的に設定します。
  public function __construct(
    private string $title,
    private string $body,
    private string $token,
    private string $language,
    private ?int $id = null,
    private ?DateTime $expirationDateTime = null,
    private ?DataTimeStamp $timeStamp = null,
  ) {}

  public function getId(): ?int
  {
    return $this->id;
  }

  public function setId(int $id): void
  {
    $this->id = $id;
  }

  public function getTitle(): string
  {
    return $this->title;
  }

  public function setTitle(string $title): void
  {
    $this->title = $title;
  }

  public function getBody(): string
  {
    return $this->body;
  }

  public function setBody(string $body): void
  {
    $this->body = $body;
  }

  public function getToken(): string
  {
    return $this->token;
  }

  public function setToken(string $token): void
  {
    $this->token = $token;
  }

  public function getLanguage(): string
  {
    return $this->language;
  }

  public function setLanguage(string $language): void
  {
    $this->language = $language;
  }

  public function getExpirationDateTime(): ?DateTime
  {
    return $this->expirationDateTime;
  }

  public function setExpirationDateTime(?DateTime $expirationDateTime): void
  {
    $this->expirationDateTime = $expirationDateTime;
  }

  public function getTimeStamp(): ?DataTimeStamp
  {
    return $this->timeStamp;
  }

  public function setTimeStamp(DataTimeStamp $timeStamp): void
  {
    $this->timeStamp = $timeStamp;
  }

  public static function validateExpirationInput($expiration): bool
  {
    if ($expiration === '') {
      return true;
    }
    // 許可する例: 1day, 2days, 1hour, 3hours, 1minute, 30seconds, 2weeks
    //   1-3桁の数字 + 単位の文字列
    //   （最大値は 999 weeks。長期すぎる値は許可しない方針）
    $pattern = '/^\d{1,3}\s*(day|days|hour|hours|minute|minutes|second|seconds|week|weeks)$/i';

    return preg_match($pattern, $expiration);
  }
}
