<?php

session_start();

$showModal = isset($_SESSION['snippet_token']);
$createdUrl = $showModal ? BASE_URL . '/' . $_SESSION['snippet_token'] : '';

$errorMessage = $_SESSION['error_message'] ?? null;

$languageOptions = [
  'c',
  'cpp',
  'csharp',
  'css',
  'dockerfile',
  'html',
  'java',
  'javascript',
  'json',
  'markdown',
  'php',
  'python',
  'ruby',
  'shell',
  'sql',
  'typescript',
  'xml',
  'yaml',
];

$expirationOptions = [
  "設定しない" => "",
  "30 秒" => "30seconds", // 開発用
  "10 分" => "10minutes",
  "1 時間" => "1hour",
  "1 日" => "1day",
  "3 日" => "3days"
];
?>

<script src="https://cdn.jsdelivr.net/npm/monaco-editor@0.32.1/min/vs/loader.js"></script>

<?php if (isset($errorMessage)) : ?>
  <div class="error-message">
    <div class="error-text-wrapper">
      <p><?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?></p>
    </div>
  </div>
<?php
  unset($_SESSION['error_message']);
endif; ?>

<div class="index-container">
  <form id="codeForm" method="post" action="/submit">
    <div class="select-wrapper">
      <select id="languageSelect" name="language" class="language-select">
        <option value="plaintext" selected>Plain Text</option>
        <?php foreach ($languageOptions as $language) : ?>
          <option value="<?= htmlspecialchars($language) ?>"><?= htmlspecialchars($language) ?></option>
        <?php endforeach; ?>
      </select>
      <label for="languageSelect"><- プログラミング言語を選択できます</label>
    </div>

    <div id="editor"></div>
    <input type="hidden" id="body" name="body" required>

    <div class="below-editor-wrapper">
      <div class="title-wrapper">
        <div class="lower-label">
          <label for="title" class="lower-label">タイトル:</label>
        </div>
        <input type="text" id="title" name="title" required>
      </div>

      <div class="expiration-wrapper">
        <div class="lower-label">
          <label for="expiration" class="lower-label">有効期限:</label>
        </div>
        <select id="expiration" name="expiration">
          <?php foreach ($expirationOptions as $text => $value) : ?>
            <option value="<?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($text, ENT_QUOTES, 'UTF-8') ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="submit-button-wrapper">
        <button type="submit" class="submit-button">作成</button>
      </div>
    </div>
  </form>
</div>

<?php if ($showModal) : ?>
  <section id="modalArea" class="modal-area">
    <div id="modalBg" class="modal-bg"></div>
    <div class="modal-wrapper">
      <div class="modal-contents">
        <p class="modal-title">code を誰かに共有しよう！</p>

        <div class="url-wrapper">
          <a href="<?= htmlspecialchars($createdUrl, ENT_QUOTES, 'UTF-8') ?>"
            id="urlLink">
            <?= htmlspecialchars($createdUrl, ENT_QUOTES, 'UTF-8') ?>
          </a>
        </div>
        <button id="copyButton" class="copy-button">URLをコピー</button>
        <div id="closeModal" class="close-modal">
          ×
        </div>
      </div>
  </section>

<?php
  unset($_SESSION['snippet_token']);
endif;
?>

<script src="/js/script.js"></script>