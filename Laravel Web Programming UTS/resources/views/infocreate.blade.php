@extends('BootStrapTemplate.app') 
@section('judulpage', 'Tambah Data Teman ')
@section('konten')

<div class="container">

    <div class="menu">
            <a href="./">Home</a>
            <a href="./info">Info</a>
    </div>

    <div class="konten">
         <h3>Tambah Data Teman</h3>
         <form action="{{route('infoteman)}}" method="POST">
            @csrf

            namateman
            <input type="text" name="namateman" required>
            Alamat
            <input type="text" name="alamat" required>
            Kota
            <input type="text" name="kota" required>
            WA
            <input type="text" name="wa" required>

            <button type ="Submit">Simpan Data</button>
         </form>
    </div>
</div>
@endsection
