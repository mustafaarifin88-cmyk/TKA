<?php

namespace App\Controllers;

class Tutorial extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Panduan Penggunaan Aplikasi',
        ];
        return view('tutorial/index', $data);
    }
}