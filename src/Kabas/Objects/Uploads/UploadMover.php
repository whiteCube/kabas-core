<?php 

namespace Kabas\Objects\Uploads;

class UploadMover
{
    public function move($tmp, $src)
    {
        return move_uploaded_file($tmp, $src);
    }

    public function copy($from, $to)
    {
        return copy($from, $to);
    }
}