<?php
// Common scripts methods.

/**
 * Get image name associated to his ID.
 *
 * @param $imageID
 * @return string
 */
function extractImageNameFrom($imageID)
{
    $idPos = strpos($imageID,'._copyrighted-image');
    if ($idPos == false) {
        $idPos = strpos($imageID,'._image');
    }
    return substr($imageID, 0, $idPos);
}