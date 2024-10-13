<?php

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
    <input type="hidden" id="body" name="body">

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
</div>



<script>
  require.config({
    paths: {
      'vs': 'https://cdn.jsdelivr.net/npm/monaco-editor@0.32.1/min/vs'
    }
  });

  let editorInstance;

  require(['vs/editor/editor.main'], function() {
    editorInstance = monaco.editor.create(document.getElementById('editor'), {
      value: "// Type your code here...",
      language: 'plaintext', // Choose the language syntax you need
      theme: 'vs-light', // Set a theme, can be 'vs-light', vs-dark or others
      automaticLayout: true
    });

    // Event listener for the dropdown menu to switch languages
    document.getElementById('languageSelect').addEventListener('change', function(event) {
      const newLanguage = event.target.value;
      const model = editorInstance.getModel();

      // Switch the language of the editor
      monaco.editor.setModelLanguage(model, newLanguage);
    });

    document.getElementById('codeForm').addEventListener('submit', function(event) {
      // フォーム送信前にエディターの内容を隠しフィールドに設定
      document.getElementById('body').value = editorInstance.getValue();
    });

  });
</script>
</form>