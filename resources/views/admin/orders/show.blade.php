@extends('layouts.app')

@section('title','Order #' . $order->id)

@section('content')

<div class="d-flex justify-content-between">
    <h1>Order #{{ $order->id }}</h1>
    <div>
        <form method="post" action="{{ route('admin.orders.updateStatus', $order) }}" class="d-inline">
            @csrf
            <select name="status" class="form-select d-inline-block w-auto" onchange="this.form.submit()">
                <option value="Pending" {{ $order->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                <option value="Completed" {{ $order->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                <option value="Cancelled" {{ $order->status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </form>
    </div>
</div>

<div class="mb-3">
    <strong>Customer:</strong> {{ $order->customer->name }} <br>
    <strong>Email:</strong> {{ $order->customer->email }} <br>
    <strong>Phone:</strong> {{ $order->customer->phone }}
</div>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Line Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->items as $item)
        <tr>
            <td>{{ $item->product->name }}</td>
            <td>{{ number_format($item->price,2) }}</td>
            <td>{{ $item->quantity }}</td>
            <td>{{ number_format($item->price * $item->quantity,2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="text-end">
    <h4>Total: {{ number_format($order->total_amount,2) }}</h4>
</div>

<a href="{{ route('admin.orders.index') }}" class="btn btn-link">Back</a>
@endsection