@extends('layouts.app')

@section('title','Create Order')

@section('content')

<h1>Create Order</h1>
<form method="post" action="{{ route('admin.orders.store') }}" id="order-form">
    @csrf

    <div class="mb-3">
        <label for="customer_id" class="form-label">Customer</label>
        <select name="customer_id" id="customer_id" class="form-select" required>
            <option value="">-- Select Customer --</option>
            @foreach($customers as $c)
            <option value="{{ $c->id }}" {{ old('customer_id') == $c->id ? 'selected' : '' }}>
                {{ $c->name }} ({{ $c->email }})
            </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="order_date" class="form-label">Order Date</label>
        <input type="date" name="order_date" id="order_date" class="form-control" value="{{ old('order_date', now()->format('Y-m-d')) }}">
    </div>

    <h5>Products</h5>
    <table class="table" id="items-table">
        <thead>
            <tr>
                <th style="width:40%">Product</th>
                <th style="width:15%">Price</th>
                <th style="width:15%">Quantity</th>
                <th style="width:15%">Line Total</th>
                <th style="width:15%"></th>
            </tr>
        </thead>
        <tbody>
            @if(old('items'))
            @foreach(old('items') as $index => $it)
            <tr>
                <td>
                    <select class="form-select product-select" name="items[{{ $index }}][product_id]" required>
                        <option value="">-- choose --</option>
                        @foreach($products as $p)
                        <option value="{{ $p->id }}" data-price="{{ $p->price }}" data-stock="{{ $p->stock_quantity }}" {{ (int)$it['product_id'] === $p->id ? 'selected' : '' }}>
                            {{ $p->name }}
                        </option>
                        @endforeach
                    </select>
                </td>
                <td><input type="number" step="0.01" name="items[{{ $index }}][price]" class="form-control price-input" value="{{ $it['price'] }}"></td>
                <td><input type="number" name="items[{{ $index }}][quantity]" class="form-control qty-input" value="{{ $it['quantity'] }}"></td>
                <td class="line-total">0.00</td>
                <td><button type="button" class="btn btn-danger btn-sm remove-row">Remove</button></td>
            </tr>
            @endforeach
            @else
            <tr>
                <td>
                    <select class="form-select product-select" name="items[0][product_id]" required>
                        <option value="">-- choose --</option>
                        @foreach($products as $p)
                        <option value="{{ $p->id }}" data-price="{{ $p->price }}" data-stock="{{ $p->stock_quantity }}">
                            {{ $p->name }}
                        </option>
                        @endforeach
                    </select>
                </td>
                <td><input type="number" step="0.01" name="items[0][price]" class="form-control price-input"></td>
                <td><input type="number" name="items[0][quantity]" class="form-control qty-input" value="1"></td>
                <td class="line-total">0.00</td>
                <td><button type="button" class="btn btn-danger btn-sm remove-row">Remove</button></td>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="mb-3">
        <button type="button" id="add-row" class="btn btn-secondary">Add Product</button>
    </div>

    <div class="mb-3">
        <label class="form-label">Total Amount</label>
        <div><strong id="total-amount">0.00</strong></div>
    </div>

    <div>
        <button type="submit" class="btn btn-primary">Create Order</button>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-link">Back</a>
    </div>
</form>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const productsData = {};
        document.querySelectorAll('#items-table select.product-select option[data-price]').forEach(opt => {
            productsData[opt.value] = {
                price: parseFloat(opt.dataset.price || 0),
                stock: parseInt(opt.dataset.stock || 0)
            };
        });

        function recalcLine(row) {
            const qtyEl = row.querySelector('.qty-input');
            const priceEl = row.querySelector('.price-input');
            const lineTotalEl = row.querySelector('.line-total');

            const qty = parseInt(qtyEl.value) || 0;
            const price = parseFloat(priceEl.value) || 0;
            const total = (qty * price).toFixed(2);
            lineTotalEl.textContent = total;
        }

        function recalcTotal() {
            let total = 0;
            document.querySelectorAll('#items-table tbody tr').forEach(r => {
                total += parseFloat(r.querySelector('.line-total').textContent || 0);
            });
            document.getElementById('total-amount').textContent = total.toFixed(2);
        }

        function calcAll() {
            document.querySelectorAll('#items-table tbody tr').forEach(recalcLine);
            recalcTotal();
        }

        document.getElementById('items-table').addEventListener('change', function(e) {
            if (e.target.matches('.product-select')) {
                const row = e.target.closest('tr');
                const priceInput = row.querySelector('.price-input');
                const selected = e.target.value;
                if (productsData[selected]) {
                    priceInput.value = productsData[selected].price.toFixed(2);
                } else {
                    priceInput.value = '';
                }
                recalcLine(row);
                recalcTotal();
            }
        });

        document.getElementById('items-table').addEventListener('input', function(e) {
            if (e.target.matches('.qty-input') || e.target.matches('.price-input')) {
                const row = e.target.closest('tr');
                recalcLine(row);
                recalcTotal();
            }
        });

        document.getElementById('add-row').addEventListener('click', function() {
            const tbody = document.querySelector('#items-table tbody');
            const index = tbody.querySelectorAll('tr').length;
            const tr = document.createElement('tr');
            tr.innerHTML = `
            <td>
                <select class="form-select product-select" name="items[${index}][product_id]" required>
                    <option value="">-- choose --</option>
                    @foreach($products as $p)
                        <option value="{{ $p->id }}" data-price="{{ $p->price }}" data-stock="{{ $p->stock_quantity }}">{{ $p->name }}</option>
                    @endforeach
                </select>
            </td>
            <td><input type="number" step="0.01" name="items[${index}][price]" class="form-control price-input"></td>
            <td><input type="number" name="items[${index}][quantity]" class="form-control qty-input" value="1"></td>
            <td class="line-total">0.00</td>
            <td><button type="button" class="btn btn-danger btn-sm remove-row">Remove</button></td>
        `;
            tbody.appendChild(tr);
        });

        document.getElementById('items-table').addEventListener('click', function(e) {
            if (e.target.matches('.remove-row')) {
                const tr = e.target.closest('tr');
                tr.remove();
                document.querySelectorAll('#items-table tbody tr').forEach((r, i) => {
                    r.querySelectorAll('select, input').forEach(el => {
                        const name = el.getAttribute('name');
                        if (!name) return;
                        const newName = name.replace(/items\[\d+\]/, `items[${i}]`);
                        el.setAttribute('name', newName);
                    });
                });
                calcAll();
            }
        });

        calcAll();
    });
</script>
@endpush