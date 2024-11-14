<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function apiDetails()
    {
        $uptime = exec('uptime -p'); // Tempo online do servidor
        $lastCronRun = DB::table('import_histories')->latest('imported_at')->value('imported_at');
        $memoryUsage = memory_get_usage();

        return response()->json([
            'status' => 'OK',
            'database_connection' => DB::connection()->getPdo() ? 'connected' : 'disconnected',
            'last_cron_run' => $lastCronRun,
            'uptime' => $uptime,
            'memory_usage' => $memoryUsage
        ]);
    }

    public function index()
    {
        $products = Product::paginate(10);
        return response()->json($products);
    }

    public function show($code)
    {
        $product = Product::where('code', $code)->firstOrFail();
        return response()->json($product);
    }

    public function update(Request $request, $code)
    {
        $product = Product::where('code', $code)->firstOrFail();
        $product->update($request->all());
        return response()->json(['message' => 'Product updated successfully', 'product' => $product]);
    }

    public function destroy($code)
    {
        $product = Product::where('code', $code)->firstOrFail();
        $product->update(['status' => 'trash']);
        return response()->json(['message' => 'Product status changed to trash']);
    }
}

