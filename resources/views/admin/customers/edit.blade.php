@extends('layouts.app')

@section('title','Edit Customer')

@section('content')

<h1>Edit Customer</h1>

<form method="post" action="{{ route('admin.customers.update', $customer) }}">
    @csrf @method('PUT')
    <div class="mb-3">
        <label class="form-label">Name</label>
        <input name="name" class="form-control" value="{{ old('name', $customer->name) }}" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Email</label>
        <input name="email" type="email" class="form-control" value="{{ old('email', $customer->email) }}" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Phone</label>
        <input name="phone" class="form-control" value="{{ old('phone', $customer->phone) }}">
    </div>
    <button class="btn btn-primary">Update</button>
    <a href="{{ route('admin.customers.index') }}" class="btn btn-link">Back</a>
</form>
@endsection