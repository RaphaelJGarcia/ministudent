<?php
require 'database.php';
$id = $_GET['id'] ?? null;
if ($id) {
    $stmt = $pdo->prepare('SELECT photo FROM students WHERE id = ?');
    $stmt->execute([$id]);
    $student = $stmt->fetch();
    if ($student && $student['photo']) unlink('uploads/' . $student['photo']);

    $stmt = $pdo->prepare('DELETE FROM students WHERE id = ?');
    $stmt->execute([$id]);
}
header('Location: index.php');
?>
