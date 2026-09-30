<?php

if (!function_exists('is_external_image')) {
    /** True for full web links (http/https, protocol-relative) and data: URIs. */
    function is_external_image(string $path): bool
    {
        return (bool) preg_match('#^(https?:)?//|^data:#i', $path);
    }
}

if (!function_exists('image_url')) {
    /**
     * URL for a stored image: local paths ("assets/...") go through base_url(),
     * full web links are returned unchanged.
     */
    function image_url(string $path): string
    {
        return is_external_image($path) ? $path : base_url($path);
    }
}
