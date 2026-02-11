<?php

namespace App\Controllers;

class Setup extends BaseController
{
    public function index()
    {
        $password = '123456';
        
        // Menggunakan algoritma PASSWORD_DEFAULT (biasanya Bcrypt)
        // Ini adalah standar keamanan yang direkomendasikan untuk CI4
        $hash = password_hash($password, PASSWORD_DEFAULT);

        echo "<h1>Generate Password Hash</h1>";
        echo "<p>Password Asli: <strong>" . $password . "</strong></p>";
        echo "<p>Password Hash (Copy ini ke database):</p>";
        echo "<textarea cols='60' rows='2'>" . $hash . "</textarea>";
        
        echo "<br><br><hr>";
        echo "<p><em>Tips: Hash ini akan selalu berbeda setiap kali Anda refresh halaman karena salt yang dinamis, tetapi semuanya valid untuk password '123456'.</em></p>";
    }
}