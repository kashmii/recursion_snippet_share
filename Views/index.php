<!-- views/create.php -->
<h1>Snippet作成 index</h1>

<form method="post" action="/submit">
  <label for="title">Title:</label>
  <input type="text" id="title" name="title" required>

  <label for="body">Body:</label>
  <textarea id="body" name="body" required></textarea>

  <label for="language">Language:</label>
  <input type="text" id="language" name="language" required>

  <!-- 文字を時間に変換する処理が必要 -->
  <label for="expiration">Expiration:</label>
  <select id="expiration" name="expiration">
    <option value="">設定しない</option>
    <option value="30seconds">30 秒</option> <!-- 開発用 -->
    <option value="10minutes">10 分</option>
    <option value="1hour">1 時間</option>
    <option value="1day">1 日</option>
    <option value="3days">3 日</option>
  </select>

  <button type="submit">作成</button>
</form>