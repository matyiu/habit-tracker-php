<?php

function uuid(int $length = 8)
{
    $bytes = random_bytes($length);

    return bin2hex($bytes);
}