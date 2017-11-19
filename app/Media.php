<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    public function getAsDataAttribute()
    {
        $data = base64_encode(file_get_contents($this->path));
        return 'data: ' . mime_content_type($this->path) . ';base64,' . $data;
    }
}
