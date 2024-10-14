require.config({
  paths: {
    vs: 'https://cdn.jsdelivr.net/npm/monaco-editor@0.32.1/min/vs',
  },
});

let editorInstance;

require(['vs/editor/editor.main'], function () {
  editorInstance = monaco.editor.create(document.getElementById('editor'), {
    value: '// Type your code here...',
    language: 'plaintext', // Choose the language syntax you need
    theme: 'vs-light', // Set a theme, can be 'vs-light', vs-dark or others
    automaticLayout: true,
  });

  // Event listener for the dropdown menu to switch languages
  document
    .getElementById('languageSelect')
    .addEventListener('change', function (event) {
      const newLanguage = event.target.value;
      const model = editorInstance.getModel();

      // Switch the language of the editor
      monaco.editor.setModelLanguage(model, newLanguage);
    });

  document
    .getElementById('codeForm')
    .addEventListener('submit', function (event) {
      // フォーム送信前にエディターの内容を隠しフィールドに設定
      document.getElementById('body').value = editorInstance.getValue();
    });

  // ================
  // モーダルの処理
  // ================

  const modal = document.getElementById('modalArea');

  if (modal) {
    const closeModalBtn = document.getElementById('closeModal');
    const modalBg = document.getElementById('modalBg');

    closeModalBtn.addEventListener('click', function () {
      modal.style.display = 'none';
    });

    modalBg.addEventListener('click', function () {
      modal.style.display = 'none';
    });
  }
});
