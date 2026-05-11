<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Product, Category, Order, OrderItem};
use Illuminate\Support\Facades\{Auth, Http, DB};
use Midtrans\Config;
use Midtrans\Snap;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::where('stock', '>', 0)->latest()->get();
        $categories = Category::all(); 
        return view('welcome', compact('products', 'categories'));
    }

    /**
     * Menampilkan halaman keranjang belanja
     */
    public function cart()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        foreach($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        return view('cart', compact('cart', 'total'));
    }

    /**
     * Update jumlah produk di keranjang (Digunakan oleh AJAX)
     */
    public function updateCart(Request $request)
    {
        if($request->id && $request->quantity){
            $cart = session()->get('cart');
            
            // Validasi stok produk sebelum update session
            $product = Product::find($request->id);
            if($product->stock < $request->quantity) {
                return response()->json(['error' => 'Stok tidak mencukupi'], 400);
            }

            $cart[$request->id]["quantity"] = $request->quantity;
            session()->put('cart', $cart);
            
            return response()->json(['success' => 'Keranjang diperbarui']);
        }
    }

    /**
     * Menghapus produk dari keranjang
     */
    public function removeFromCart(Request $request)
    {
        if($request->id) {
            $cart = session()->get('cart');
            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            return response()->json(['success' => 'Produk dihapus']);
        }
    }

    public function checkout()
    {
        $cart = session()->get('cart');
        if (!$cart) return redirect('/')->with('error', 'Keranjang belanja kosong!');

        $subtotal = 0;
        foreach($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        // Ambil data kota dari RajaOngkir (Opsional jika Anda memakainya)
        try {
            $response = Http::withHeaders(['key' => env('RAJAONGKIR_API_KEY')])
                          ->get('https://api.rajaongkir.com/starter/city');
            $cities = $response->json()['rajaongkir']['results'] ?? [];
        } catch (\Exception $e) {
            $cities = [];
        }

        return view('checkout', compact('cart', 'cities', 'subtotal'));
    }

    public function addToCart(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $quantity = $request->input('quantity', 1);

        if ($product->stock < $quantity) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi!');
        }

        $cart = session()->get('cart', []);

        if(isset($cart[$id])) {
            $cart[$id]['quantity'] += $quantity;
        } else {
            $cart[$id] = [
                "name" => $product->name,
                "quantity" => $quantity,
                "price" => $product->price,
                "image" => $product->image,
                "product_id" => $product->id
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Produk berhasil ditambahkan!');
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'shipping_method' => 'required',
        ]);

        $cart = session()->get('cart');
        if (!$cart) return redirect('/')->with('error', 'Sesi habis.');

        $subtotal = 0;
        foreach($cart as $item) { 
            $subtotal += $item['price'] * $item['quantity']; 
        }

        $shipping_cost = (int) $request->shipping_cost;
        $total_bayar = $subtotal + $shipping_cost;
        $orderId = 'ALTIK-' . time();

        DB::beginTransaction();
        try {
            $order = Order::create([
                'order_id' => $orderId,
                'user_id' => Auth::id(),
                'customer_name' => $request->name,
                'customer_phone' => $request->phone,
                'address' => $request->address,
                'shipping_method' => $request->shipping_method,
                'shipping_cost' => $shipping_cost,
                'total_price' => $total_bayar,
                'status' => 'Pending',
            ]);

            foreach ($cart as $id => $details) {
                // Ambil data produk asli dari database
                $product = Product::find($id);

                if ($product) {
                    OrderItem::create([
                        'order_id'       => $order->id,
                        'product_id'     => $id,
                        'user_id'        => $product->user_id, // PEMILIK BARANG DICATAT DISINI
                        'product_name'   => $product->name,
                        'price'          => $details['price'],
                        'purchase_price' => $product->purchase_price, // MODAL DICATAT DISINI
                        'quantity'       => $details['quantity'],
                    ]);

                    // Kurangi stok produk
                    $product->decrement('stock', $details['quantity']);
                }
            } // <--- PENUTUP FOREACH ITEM HARUS DI SINI

            // Konfigurasi Midtrans
            Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            Config::$isProduction = false;
            Config::$isSanitized = true;
            Config::$is3ds = true;

            $params = [
                'transaction_details' => ['order_id' => $orderId, 'gross_amount' => (int)$total_bayar],
                'customer_details' => [
                    'first_name' => $request->name,
                    'phone' => $request->phone,
                ],
            ];

            $snapToken = Snap::getSnapToken($params);
            
            DB::commit();
            session()->forget('cart');

            return view('checkout', [
                'snapToken' => $snapToken,
                'order' => $order,
                'subtotal' => $subtotal,
                'cart' => [] 
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function myOrders(Request $request)
    {
        $phone = $request->get('phone');
        $query = Order::with('items')->latest();

        if (Auth::check()) {
            $orders = $query->where('user_id', Auth::id())->get();
        } elseif ($phone) {
            $orders = $query->where('customer_phone', $phone)->get();
        } else {
            $orders = [];
        }

        return view('my-orders', compact('orders'));
    }
}