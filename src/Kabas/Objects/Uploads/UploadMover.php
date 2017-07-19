<?php 

namespace Kabas\Objects\Uploads;

class UploadMover
{
    /**
     * Wrapper for move_uploaded_file
     * @param string $tmp 
     * @param string $src 
     * @return bool
     * @codeCoverageIgnore
     */
    public function move($tmp, $src)
    {
        return move_uploaded_file($tmp, $src);
    }

    /**
     * Wrapper for copy
     * @param string $from 
     * @param string $to 
     * @return bool
     * @codeCoverageIgnore
     */
    public function copy($from, $to)
    {
        return copy($from, $to);
    }
}