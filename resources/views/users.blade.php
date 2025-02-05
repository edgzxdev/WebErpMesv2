@extends('adminlte::page')

@section('title', 'Users')

@section('content_header')
    <h1>User list</h1>
@stop

@section('content')
    <div class="card">
        <!-- /.card-header -->
        <div class="row">
            @foreach ($Users as $User)
            <div class="col-4">
              <x-adminlte-profile-widget name="{{ $User->name }}" desc="{{ $User->GetPrettyCreatedAttribute() }}" theme="primary"
                img="{{ $User->adminlte_image() }}">
                <x-adminlte-profile-col-item class="text-primary border-right" icon="far fa-envelope" title="E-mail" text="{{ $User->email }}" size=6 badge="primary"/>
                <x-adminlte-profile-col-item class="text-danger" icon="fas fa-lg fa-phone" title="Phone" text="{{ $User->personnal_phone_number }}" size=6 badge="danger"/>
              </x-adminlte-profile-widget>
            </div>
            @endforeach
        </div>
        <!-- /.row -->
        <div class="row">
          <div class="col-5">
            {{ $Users->links() }}
          </div>
        </div>
        <!-- /.row -->
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
@stop

@section('css')
@stop

@section('js')
@stop