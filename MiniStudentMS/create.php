<?php
require 'database.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $photoName = null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        if (!is_dir('uploads')) mkdir('uploads', 0755);
        $photoName = time() . '_' . basename($_FILES['photo']['name']);
        move_uploaded_file($_FILES['photo']['tmp_name'], __DIR__ . '/uploads/' . $photoName);
    }
    $stmt = $pdo->prepare('INSERT INTO students (name, photo) VALUES (?, ?)');
    $stmt->execute([$name, $photoName]);
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
  <title>Add Student</title>
</head>
<body class="p-4">
<div class="container">
  <h1>Add Student</h1>
  <form method="post" enctype="multipart/form-data">
    <div class="mb-3">
      <label class="form-label">Name</label>
      <input type="text" name="name" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Photo</label>
      <input type="file" name="photo" class="form-control">
    </div>
    <button type="submit" class="btn btn-success">Save</button>
    <a href="index.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
