<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>Verifikasi Email</h1>
    
    <br>
    <p>Selamat datang {{ $data['nama'] }}</p>
    <br>

    <p>Anda telah terdaftar sebagai pengguna SIMDATUK (Sistem Informasi Manajemen Data Dukungan Kepegawaian)</p>
    <p>Silahkan klik tombol di bawah untuk melakukan verifikasi email.</p>

    <a href="{{ $data['base_url'] . '/' . $data['verification_key'] }}">
        <button type="button">Verifikasi Email</button>
    </a>

    <p>Verifikasi berlaku sampai : {{ $data['expired_at'] }}</p>

    <p>Jika Anda mengalami kendala dengan tombol di atas, Anda bisa klik link di bawah ini.</p>
    <a href="{{ $data['base_url'] . '/' . $data['verification_key'] }}">
        {{ $data['base_url'] . '/' . $data['verification_key'] }}
    </a>

    <p>Terimakasih</p>

    <br>
    <p><b>SIMDATUK</b></p>

    <br>
    <br>

    <p>Harap jangan membalas e-mail ini, karena e-mail ini dikirimkan secara otomatis oleh sistem.</p>

</body>
</html>