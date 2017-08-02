<?php

function placeholder($width, $height = false)
{
    $height = !$height ? $width : $height;
    return "<img src=\"http://via.placeholder.com/${width}x${height}\" alt=\"placeholder\">";
}

