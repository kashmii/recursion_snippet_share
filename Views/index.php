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
  <!-- <label for="expiration">Expiration (optional):</label>
  <select id="expiration" name="expiration">
    <option value="10m">10 分</option>
    <option value="1h">1 時間</option>
    <option value="1d">1 日</option>
    <option value="forever">永続</option>
  </select> -->

  <button type="submit">作成</button>
</form>