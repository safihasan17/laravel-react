@extends('admin.layouts.master')

@section('title', 'products - Edit')



@section('content')

    <x-admin.phead title="products Edit" subtitle="update this information">

        <a href="{{ route('products.index') }}" class="btn-custom btn-custom-secondary" type="button"> <i class="bi bi-plus"></i>
            back to products</a>

    </x-admin.phead>

    <form action="{{ route('products.update', ['user' => $user->id]) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="basicText" class="form-label-custom">Name</label>
            <input type="text" name="name" class="form-control-custom" id="basicText" placeholder="Enter username"
                value= "{{ $user->name }}">
            <x-admin.error-msg name="name" />
        </div>

        <!-- Email input -->
        <div class="mb-3">
            <label class="form-label-custom">Email Address</label>
            <input type="text" name="email" class="form-control-custom" placeholder="name@example.com"
                value= "{{ $user->email }}">
            <x-admin.error-msg name="email" />
        </div>

        <!-- Role input -->
        <div class="mb-3">
            <label class="form-label-custom">Role</label>
            <select name="role_id" class="form-select-custom">
                <option value="0" selected disabled>Select a role</option>
                @foreach ($roles as $item)
                    <option value="{{ $item->id }}" @selected($user->role_id == $item->id)>{{ $item->name }}</option>
                @endforeach

            </select>
            <x-admin.error-msg name="role" />
        </div>


        <div class="mb-3 text-end">
            <button type="submit" class="btn-custom btn-custom-secondary">Save</button>
        </div>
    </form>
@endsection
