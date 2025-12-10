@extends('layouts.app')

@section('title','Create Customer')

@section('content')

<h1>Create Customer</h1>
<form method="post" action="{{ route('admin.customers.store') }}">
    @csrf
    <div class="mb-3">
        <label class="form-label">Name</label>
        <input name="name" class="form-control" value="{{ old('name') }}" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Email</label>
        <input name="email" type="email" class="form-control" value="{{ old('email') }}" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Phone</label>
        <input name="phone" class="form-control" value="{{ old('phone') }}">
    </div>
    <button class="btn btn-primary">Create</button>
    <a href="{{ route('admin.customers.index') }}" class="btn btn-link">Back</a>
</form>
@endsection