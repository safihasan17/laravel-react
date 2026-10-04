@extends('admin.layouts.master')

@section('title', 'Role - Edit')



@section('content')

    <x-admin.phead title="Role -  Edit" subtitle="update this information">
       
        <a href="{{ route('roles.index') }}" class="btn-custom btn-custom-secondary" type="button"> <i class="bi bi-plus"></i>
            back to role</a>
       
    </x-admin.phead>

    <form action="{{ route('roles.update', ['role' => $role->id]) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="basicText" class="form-label-custom">Name</label>
            <input type="text" name="name" class="form-control-custom" id="basicText" placeholder="Enter username"
                value= "{{ $role->name }}">
            <x-admin.error-msg name="name" />
        </div>

        <!-- Email input -->
        

        <!-- Role input -->
        


        <div class="mb-3 text-end">
            <button type="submit" class="btn-custom btn-custom-secondary">Update</button>
        </div>
    </form>
@endsection
