<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreOrderRequest;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

use function Symfony\Component\Clock\now;

class OrderController extends Controller
{
    public function index(): View
    {
        $orders = Order::with('customer')->withCount('items')->orderByDesc('created_at')->paginate(5);
        return view('admin.orders.index',compact('orders'));
    }

    public function create(): View
    {
        $customers = Customer::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        return view('admin.orders.create',compact('customers','products'));
    }

    public function store(StoreOrderRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $items = $data['items'];

        DB::beginTransaction();

        try{
            $productIds = collect($items)->pluck('product_id')->unique()->all();
            $products = Product::whereIn('id',$productIds)->lockForUpdate()->get()->keyBy('id');

            $total = 0;
            foreach($items as $i => $it) {
                $product = $products[$it['product_id']] ?? null;
                if(!$product) {
                    DB::rollBack();
                    return back()->withInput()->withErrors("Product with id {$it['product_id']} not found.");
                }

                if($it['quantity'] > $product->stock_quantity) {
                    DB::rollBack();
                    return back()->withInput()->withErrors("Not enough stock for product: {$product->name}. Available: {$product->stock_quantity}");
                }

                $total += $it['quantity'] * $it['price'];
            }

            $order = Order::create([
                'customer_id' => $data['customer_id'],
                'total_amount' => $total,
                'order_date' => $data['order_date'] ?? now()->toDateString(),
                'status' => 'Pending',
            ]);

            foreach($items as $it) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $it['product_id'],
                    'quantity' => $it['quantity'],
                    'price' => $it['price'],
                ]);

                $product = $products[$it['product_id']];
                $product->decrement('stock_quantity',$it['quantity']);
            }
            DB::commit();
            return redirect()->route('admin.orders.show', $order)->with('success', 'Order created successfully.');

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->withErrors('Error creating order: ' . $e->getMessage());
        }
    }

    public function show(Order $order): View
    {
        $order->load(['customer','items.product']);
        return view('admin.orders.show',compact('order'));
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:Pending,Completed,Cancelled',
        ]);

        $order->update(['status' => $request->post('status')]);
        return redirect()->route('admin.orders.show',$order)->with('success','Order status updated.');
    }
}
