<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'nisn',
        'nis',
        'nama',
        'kelas',
        'jenis_kelamin',
        'alamat',
        'phone',
    ];

public function user()
{
    return $this->hasOne(User::class, 'student_id');
}


}