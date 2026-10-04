@extends('admin.layouts.master')

@section('title', 'Role - create')



@section('content')

    <x-admin.phead title="Role create" subtitle="create roles  ">

        <a href="{{ route('roles.index') }}" class="btn-custom btn-custom-secondary" type="button"> <i class="bi bi-plus"></i>
            back to Roles</a>

    </x-admin.phead>

    @if (session('error'))
       <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="close"></button>
       </div>
    @endif

    <form action="{{ route('roles.store') }}" method="POST">
        @csrf
        {{-- @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif --}}
        <!-- Text input -->
        <div class="mb-3">
            <label for="basicText" class="form-label-custom">Name</label>
            <input type="text" name="name" class="form-control-custom" id="basicText" placeholder="Enter username"
                value= "{{ old('name') }}">
            <x-admin.error-msg name="name" />
        </div>

       
       

       

        <div class="mb-3 text-end">
            <button type="submit" class="btn-custom btn-custom-secondary">Save</button>
        </div>
    </form>
@endsection
