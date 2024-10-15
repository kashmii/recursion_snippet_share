<div class="list-container">
  <div class="list-table-wrapper">
    <table>
      <thead>
        <tr>
          <th>title</th>
          <th>posted</th>
          <th>language</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($snippets as $snippet) : ?>
          <tr onclick="location.href='/<?php echo $snippet['token']; ?>'">
            <td><?php echo $snippet['title']; ?></td>
            <td><?php echo $snippet['created_at']; ?></td>
            <td><?php echo $snippet['language']; ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <h4 class="note">最大100件のみ表示します</h4>
</div>