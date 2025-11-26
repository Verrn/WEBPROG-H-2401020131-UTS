@extends('BootStrapTemplate.app') 
@section('judulpage', 'Info Teman - Detail')
@section('konten')

<div class="container">

<div class="card" style="width: 21rem;">
  <div class="card-body">
  <div class="card" style="width: 18rem;">
  <ul class="list-group list-group-flush">
    <li class="list-group-item">{{$dtateman["namateman"]}}</li>
    <li class="list-group-item">{{$dtateman["alamat"]}} {{$dtateman["kota"]}}</li>
    <li class="list-group-item">{{$dtateman["wa"]}}</li>
  </ul>
</div>
<div class="card-footer">
    <a class="btn btn-primary" href="{{route('infoteman') }}" role="button" >kembali</a>
</div>
</div>
@endsection