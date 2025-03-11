<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\menu;
use App\Models\order;
use Exception;
use Illuminate\Support\Facades\Gate;

class OrderController extends Controller
{
    public function index(){
        try{
            Gate::authorize('index',Order::class);
            $order=Order::with('OrderItem','user')->get();
            return response()->json(['status' => 'success', 'message' => 'Order Get successfully', 'data' => $order],200);
        }
        catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    public function show($id){
        try{
            $order=Order::with('OrderItem','user')->findOrFail($id);
            if(!$order){
                return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
            }
            return response()->json(['status' => 'success', 'message' => 'Order Get successfully', 'data' => $order],200);

        }
        catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }

    }
    public function store(Request $request)
    {
        try {
            Gate::authorize('create',Order::class);
           
            $validatedData = $request->validate([
                "status" => "nullable|in:pending,delivered,cancelled",
                "user_id" => "exists:users,id",
                "order_items" => "required|array", 
                "order_items.*.menu_id" => "exists:menus,id",
                "order_items.*.quantity" => "required|integer|min:1", 
            ]);
    
           
            $totalPrice = 0;
    
            foreach ($validatedData['order_items'] as $item) {
                $menu = Menu::findOrFail($item['menu_id']); 
                $totalPrice += $menu->price * $item['quantity']; 
            }
    
       
            $orderData = [
                "totel_price" => $totalPrice, 
                "status" => $request->input('status', 'pending'),
                "user_id" => Auth::user()->id,
            ];
    
       
            $order = Order::create($orderData);
    
            
            foreach ($validatedData['order_items'] as $item) {
                $order->OrderItem()->create([
                    'menu_id' => $item['menu_id'],
                    'quantity' => $item['quantity'],
                ]);
            }
    
            return response()->json(['status' => 'success', 'message' => 'Order created successfully', 'data' => $order], 200);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    

    public function destroy($id)
    {
        try {
            $order = order::find($id);
            if (!$order) {
                return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
            }
            if ($order->status == 'cancelled') {
                $order->delete();
                return response()->json(['status' => 'success', 'message' => 'Order deleted successfully'], 200);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    public function update(Request $request, $id)
    {
        try {
            Gate::authorize('update',order::class);
            $validatedData = $request->validate([
                "status" => "required|in:pending,delivered,cancelled",
            ]);
            $order = order::find($id);
            if (!$order) {
                return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
            }
            $order->update($validatedData);
            return response()->json(['status' => 'success', 'message' => 'Order updated successfully', 'data' => $order], 200);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
