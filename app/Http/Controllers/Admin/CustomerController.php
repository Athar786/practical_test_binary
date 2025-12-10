<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCustomerRequest;
use App\Http\Requests\Admin\UpdateCustomerRequest;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(): View
    {
        $customers = Customer::withCount('orders')->orderByDesc('created_at')->paginate(2);
        return view('admin.customers.index',compact('customers'));
    }

    public function create(): View
    {
        return view('admin.customers.create');
    }

    public function store(StoreCustomerRequest $request): RedirectResponse
    {
        Customer::create($request->validated());
        return redirect()->route('admin.customers.index')->with('success', 'Customer created.');
    }

    public function edit(Customer $customer): View
    {
        return view('admin.customers.edit',compact('customer'));
    }

    public function update(UpdateCustomerRequest $request,Customer $customer): RedirectResponse
    {
        $customer->update($request->validated());
        return redirect()->route('admin.customers.index')->with('success', 'Customer updated.');
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        $customer->delete();
        return redirect()->route('admin.customers.index')->with('success', 'Customer deleted.');
    }
}
