<?php
require 'database.php';

if (isset($_POST['delete']) && !empty($_POST['selector'])) {
    $ids = $_POST['selector'];
    $in  = str_repeat('?,', count($ids) - 1) . '?';
    $stmt = $pdo->prepare("DELETE FROM students WHERE id IN ($in)");
    $stmt->execute($ids);
    header('Location: index.php');
}

$stmt = $pdo->query('SELECT * FROM students');
$students = $stmt->fetchAll();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <title>Student Management</title>
</head>
<body class="p-4">
<div class="container">
  <h1>Students</h1>
  <a href="create.php" class="btn btn-primary mb-3">Add New</a>
  <form method="post">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th><input type="checkbox" id="select-all"></th>
          <th>ID</th>
          <th>Name</th>
          <th>Picture</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($students as $row): ?>
        <tr>
          <td><input type="checkbox" name="selector[]" value="<?= $row['id'] ?>"></td>
          <td><?= $row['id'] ?></td>
          <td><?= htmlspecialchars($row['name']) ?></td>
          <td><?php if ($row['photo']): ?>
            <img src="uploads/<?= htmlspecialchars($row['photo']) ?>" width="50">
          <?php endif; ?></td>
          <td>
            <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
            <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <button type="submit" name="delete" class="btn btn-danger">Delete Selected</button>
  </form>
</div>
<script>
document.getElementById('select-all').onclick = function() {
  var checkboxes = document.querySelectorAll('input[type="checkbox"][name="selector[]"]');
  for (var cb of checkboxes) cb.checked = this.checked;
};
</script>
</body>
</html>
