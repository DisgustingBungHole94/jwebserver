<?php
    require_once 'util.php';

    session_start();

    if (!isset($_FILES['file-upload']['name'])) {
        $_SESSION['serverError'] = 'Please select a file to upload!';
        echo '<script>top.window.location.href="../index.php";</script>';
        exit();
    }

    cleanAudioDirectory();

    $targetDir = '../audio/';
    $targetFile = $targetDir . basename($_FILES['file-upload']['name']);

    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    if (!($fileType === 'mp3' || $fileType === 'wav' || $fileType === 'ogg')) {
        $_SESSION['serverError'] = 'The selected file has an unsupported format!';
        echo '<script>top.window.location.href="../index.php";</script>';
        exit();
    }

    if (!move_uploaded_file($_FILES['file-upload']['tmp_name'], $targetFile)) {
        $_SESSION['serverError'] = 'Failed to upload the selected file!';
        echo '<script>top.window.location.href="../index.php";</script>';
        exit();
    }

    echo '<script>top.window.location.href="../index.php";</script>';
    exit();
?>