<?php
require 'database.php';
$id = $_GET['id'] ?? null;
if (!$id) header('Location: index.php');

$stmt = $pdo->prepare('SELECT * FROM students WHERE id = ?');
$stmt->execute([$id]);
$student = $stmt->fetch();
if (!$student) header('Location: index.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $photoName = $student['photo'];
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        if (!empty($photoName) && file_exists(__DIR__ . '/uploads/' . $photoName)) {
            unlink(__DIR__ . '/uploads/' . $photoName);
        }
        $photoName = time() . '_' . basename($_FILES['photo']['name']);
        move_uploaded_file($_FILES['photo']['tmp_name'], __DIR__ . '/uploads/' . $photoName);
    }
    $stmt = $pdo->prepare('UPDATE students SET name = ?, photo = ? WHERE id = ?');
    $stmt->execute([$name, $photoName, $id]);
    header('Location: index.php');
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <title>Edit Student</title>
</head>
<body class="p-4">
<div class="container">
  <h1>Edit Student</h1>
  <form method="post" enctype="multipart/form-data">
    <div class="mb-3">
      <label class="form-label">Name</label>
      <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($student['name']) ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Photo</label>
      <?php if (!empty($student['photo']) && file_exists(__DIR__ . '/uploads/' . $student['photo'])): ?>
        <div class="mb-2">
          <img src="uploads/<?= htmlspecialchars($student['photo']) ?>" class="img-thumbnail" width="100" alt="Current Photo">
        </div>
      <?php endif; ?>
      <input type="file" name="photo" class="form-control">
    </div>
    <button type="submit" class="btn btn-success">Update</button>
    <a href="index.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
