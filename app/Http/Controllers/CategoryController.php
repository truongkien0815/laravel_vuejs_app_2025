<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Flight;
use App\Models\Category;

class CategoryController extends Controller
{
     public function index()
    {
        $categories = Category::all();
        return response()->json($categories);
    }
    
     public function show($id)
    {
        $Flight = Category::find($id);
       
     
        return response()->json(
            [ 'flight' => $Flight,
      
         ]);
    }

     public function update(Request $request, $id)
    {
        $Flight = Category::find($id);
        if (!$Flight) {
            return response()->json(['message' => 'Không tìm thấy sản phẩm'], 404);
        }

        $Flight->name = $request->input('name');
        $Flight->save();

        

        return response()->json(['message' => 'Cập nhật thành công', 'image' => $Flight->image]);
    }

      public function destroy($id)
    {
        $item = Category::where('id', '=', $id);
        $item->delete();
       
    }

      public function add(Request $request)
    {
          $validated = $request->validate([
        'name' => 'required|string|max:255',
    ]);
    
        $user = new Category();
        $user->name = $request->input('name');
        $user->save();
        
         // Lấy danh sách category_ids từ request và gán vào sản phẩm
 

        return response()->json($user);
    }
}
