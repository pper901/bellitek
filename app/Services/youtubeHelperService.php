<?php

function youtubeEmbedUrl(?string $url): ?string
{
    if (!$url) return null;

    preg_match(
        '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i',
        $url,
        $matches
    );

    return $matches[1] ?? null
        ? 'https://www.youtube.com/embed/' . $matches[1]
        : null;
}
