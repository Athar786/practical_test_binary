@extends('layouts.app')

@section('title','Orders')

@section('content')

<div class="d-flex justify-content-between mb-3">
    <h1>Orders</h1>
    <a href="{{ route('admin.orders.create') }}" class="btn btn-primary">Create Order</a>
</div>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>#</th>
            <th>Customer</th>
            <th>Total</th>
            <th>Status</th>
            <th>Number of items</th>
            <th>Order Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($orders as $order)
        <tr>
            <td>{{ $order->id }}</td>
            <td>{{ $order->customer ? $order->customer->name : 'UserDeleted' }}</td>
            <td>{{ number_format($order->total_amount,2) }}</td>
            <td>{{ $order->status }}</td>
            <td>{{ $order->items_count }}</td>
            <td>{{ $order->order_date }}</td>
            <td>
                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">View</a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center">No orders found.</td>
        </tr>
        @endforelse
    </tbody>
</table>

{{ $orders->links() }}
@endsection