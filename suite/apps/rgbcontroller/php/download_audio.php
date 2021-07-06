<?php
    require_once 'util.php';

    session_start();

    if (!isset($_GET['id'])) {
        $_SESSION['serverError'] = 'No YouTube video ID was specified!';
        echo '<script>top.window.location.href="../index.php";</script>';
        exit();
    }

    $request = file_get_contents('https://www.yt-download.org/api/button/mp3/' . $_GET['id']);
    if (!$request) {
        $_SESSION['serverError'] = 'Failed to connect to YouTube API! Please try again later.';
        echo '<script>top.window.location.href="../index.php";</script>';
        exit();
    }

    $requestIndex = strpos($request, 'https://www.yt-download.org/download/' . $_GET['id'] . '/mp3/320/');
    $audioLink = substr($request, $requestIndex, 134);

    echo 'Downloading the selected song... (this might take a minute)';

    cleanAudioDirectory();

    $filePath = '../audio/';
    if (!isset($_GET['name'])) {
        $fileName = $filePath . 'YouTube Audio.mp3';
    } else {
        $fileName = $filePath . urldecode($_GET['name']) . '.mp3';
    }

    if (!file_put_contents($fileName, file_get_contents($audioLink))) {
        $_SESSION['serverError'] = 'Failed to download video from YouTube! Please try again later.';
        echo '<script>top.window.location.href="../index.php";</script>';
        exit();
    }

    echo '<script>top.window.location.href="../index.php";</script>';
    exit();
?>