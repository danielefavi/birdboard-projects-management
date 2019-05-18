<?php

function gravatar_url($email, $size=60, $default=null)
{
    $url = "https://gravatar.com/avatar/". md5($email) ."?s=$size";

    if ($default) $url .= "&d=$default";

    return $url;
}
