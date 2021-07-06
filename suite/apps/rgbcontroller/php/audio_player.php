<?php
    function displayPlayer() {
        $audioDirectory = 'audio';
        $audioFileArray = array_diff(scandir($audioDirectory), array('..', '.'));
        
        foreach($audioFileArray as $file) {
            $fileInfo = pathinfo($audioDirectory . '/' . $file);
            
            if (isset($fileInfo['extension']) && $fileInfo['extension'] === 'mp3') {
                $audioFile = $audioDirectory . '/' . $file;
                $audioFileName = $fileInfo['filename'];
                break;
            }
        }
        
        if (!isset($audioFile) || !isset($audioFileName)) {
            $currentlyPlaying = 'Nothing is currently playing.';
            $musicPlayerElement = '<audio id="music-player" controls></audio>';
        } else {
            $currentlyPlaying = 'Now playing: ' . $audioFileName;
            $musicPlayerElement = '<audio id="music-player" src="' . $audioFile . '" controls></audio>';
        }
        
        echo '<span class="text">' . $currentlyPlaying . '</span>';
        echo '<br />';
        echo '<br />';
        echo $musicPlayerElement;
    }
?>