<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bookrecord extends Model
{
    use HasFactory;
    protected $fillable = [
        'name','author_id','description','publish_date','created_by','updated_by','book_file',
    ];
    protected $table = 'bookrecord';
}
