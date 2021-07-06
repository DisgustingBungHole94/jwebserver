<?php
    function cleanAudioDirectory() {
        $targetDirFiles = glob('../audio/*');
        foreach($targetDirFiles as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }
?>