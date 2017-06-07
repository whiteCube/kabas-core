<?php

namespace TheCapricorn\Core\ImageFilter;

class Logolist implements \Intervention\Image\Filters\FilterInterface
{

    /**
     * Applies filter effects to given image
     *
     * @return Intervention\Image\Image
     */
    public function applyFilter(\Intervention\Image\Image $image)
    {
        $image->widen(140, function ($constraint) { $constraint->upsize(); });
        $image->heighten(140, function ($constraint) { $constraint->upsize(); });
        return $image;
    }
}