@extends('layouts.app')

@section('title','Customers')

@section('content')

<div class="d-flex justify-content-between mb-3">
    <h1>Customers</h1>
    <a href="{{ route('admin.customers.create') }}" class="btn btn-primary">Add Customer</a>
</div>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Created</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($customers as $c)
        <tr>
            <td>{{ $c->id }}</td>
            <td>{{ $c->name }}</td>
            <td>{{ $c->email }}</td>
            <td>{{ $c->phone }}</td>
            <td>{{ $c->created_at->format('Y-m-d') }}</td>
            <td>
                <a href="{{ route('admin.customers.edit', $c) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                <form action="{{ route('admin.customers.destroy', $c) }}" method="post" class="d-inline" onsubmit="return confirm('Are you sure you want to delete the customer?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center">No customers</td>
        </tr>
        @endforelse
    </tbody>
</table>

{{ $customers->links() }}
@endsection