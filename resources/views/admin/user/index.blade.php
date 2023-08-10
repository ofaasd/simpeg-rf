@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', $var['title'])

@section('content')
<table class="datatables-users table">
  <thead class="table-light">
    <tr>
      <th></th>
      <th>Id</th>
      <th>User</th>
      <th>Email</th>
      <th>Verified</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    @foreach($var['user'] as $user)
      <tr>
        <td></td>
        <td>{{$user->id}}</td>
        <td>{{$user->name}}</td>
        <td>{{$user->email}}</td>
        <td>{{$user->email_verified_at}}</td>
        <td><a href="" class='btn btn-primary'><i class='fa fa-pencil'></i></a><a href="" class='btn btn-danger'><i class='fa fa-trash'></i></a></td>
      </tr>
    @endforeach
  </tbody>
</table>
@endsection
