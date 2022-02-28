<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $product = auth()->user()->products;
 
        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }
 
    public function show($id)
    {
        $product = auth()->user()->products()->find($id);
 
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found '
            ], 400);
        }
 
        return response()->json([
            'success' => true,
            'data' => $product->toArray()
        ], 400);
    }
 
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'stock' => 'required'
        ]);
 
        $product = new Product();
        $product->name = $request->name;
        $product->stock = $request->stock;
 
        if (auth()->user()->products()->save($product))
            return response()->json([
                'success' => true,
                'data' => $product->toArray()
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Product not added'
            ], 500);
    }
 
    public function update(Request $request,$id)
    {
        $product = auth()->user()->products()->find($id);
        
        $this->validate($request, [
            'name' => 'required',
            'stock' => 'required'
        ]);
 
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 400);
        }
 
        $updated = $product->fill($request->all())->save();
 
        if ($updated)
            return response()->json([
                'success' => true
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Product can not be updated'
            ], 500);
    }
 
    public function destroy($id)
    {
        $product = auth()->user()->products()->find($id);
 
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 400);
        }
 
        if ($product->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Product can not be deleted'
            ], 500);
        }
    }
}
