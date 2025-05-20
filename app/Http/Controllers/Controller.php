<?php

namespace App\Http\Controllers;

abstract class Controller
{
    // Di StudentController
public function view(Student $student)
{
    if (auth()->user()->hasRole('admin') || (auth()->user()->id === $student->user_id)) {
        // Izinkan akses
        return view('students.show', compact('student'));
    } else {
        abort(403, 'Anda tidak memiliki izin untuk melihat detail siswa ini.');
    }
}
}
