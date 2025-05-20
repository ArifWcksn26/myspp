<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
    

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function departments()
    {
        return $this->belongsTo(Department::class, 'department_id'); // Pastikan 'department_id' adalah nama kolom yang benar
    }

    // Jika Anda juga ingin relasi dengan model User
   
    
}