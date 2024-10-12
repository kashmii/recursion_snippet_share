<script src="https://cdn.jsdelivr.net/npm/monaco-editor@0.32.1/min/vs/loader.js"></script>

<h1>Code Sharing</h1>

<form id="codeForm" method="post" action="/submit">
  <label for="languageSelect">Choose a programming language: </label>
  <select id="languageSelect" name="language">
    <option value="plaintext">Plain Text</option>
    <option value="php">PHP</option>
    <option value="javascript">JavaScript</option>
    <!-- Add more languages if needed -->
  </select>

  <div id="editor" style="width:800px;height:600px;border:1px solid grey;"></div>
  <input type="hidden" id="body" name="body">

  <label for="title">Title:</label>
  <input type="text" id="title" name="title" required>

  <label for="expiration">有効期限:</label>
  <select id="expiration" name="expiration">
    <option value="">設定しない</option>
    <option value="30seconds">30 秒</option> <!-- 開発用 -->
    <option value="10minutes">10 分</option>
    <option value="1hour">1 時間</option>
    <option value="1day">1 日</option>
    <option value="3days">3 日</option>
  </select>

  <button type="submit">作成</button>

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
        theme: 'vs-light' // Set a theme, can be 'vs-light', vs-dark or others
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
