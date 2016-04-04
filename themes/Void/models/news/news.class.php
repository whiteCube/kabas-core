<?php

namespace Theme\Void\Models;

use Kabas\Model\Model;

class News extends Model
{
      protected $driver = 'json';
      protected static $table = 'news';
      protected static $fillable = ['title', 'content', 'image'];
}
