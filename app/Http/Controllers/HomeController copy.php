<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Flight;
use App\Models\Category;

use function Laravel\Prompts\alert;

class HomeController extends Controller
{
    // Hàm hiển thị
    public function index_all()
    {
        $users = Flight::all();
        return response()->json($users);
    }
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10); // Mặc định mỗi trang 10 bản ghi
        $flights = Flight::orderBy('id')->paginate($perPage);
        $categories = Category::all();


        return response()->json([
            'data' => $flights->items(),
            'current_page' => $flights->currentPage(),
            'last_page' => $flights->lastPage(),
            'per_page' => $flights->perPage(),
            'total' => $flights->total(),
            'next_page_url' => $flights->nextPageUrl(),
            'prev_page_url' => $flights->previousPageUrl(),
            'categories' => $categories,
        ]);
    }

    public function about()
    {
        return view('about');
    }

    public function destroy($id)
    {
        $item = Flight::where('id', '=', $id);
        $item->delete();
        // $users = Flight::all();
        // return response()->json($users);
    }

    public function show($id)
    {
        $Flight = Flight::find($id);
        $category = $Flight->categories;
        $categories = Category::all();
        return response()->json(
            [ 'flight' => $Flight,
        'category' => $category,
        'categories' => $categories
         ]);
    }

    public function show_detail($id)
    {
        $Flight = Flight::find($id);
        $category = $Flight->categories;
        // $categories = Category::all();
        return response()->json(
            [ 'flight' => $Flight,
        'category' => $category,
        // 'categories' => $categories
         ]);
    }

    public function update(Request $request, $id)
    {
        $Flight = Flight::find($id);
        if (!$Flight) {
            return response()->json(['message' => 'Không tìm thấy sản phẩm'], 404);
        }

        $Flight->name = $request->input('name');

        // $Flight->class = $request->input('class');
        if($request->input('class') != null)
        {
        $Flight->class = $request->input('class');
        }
        else
        {
        $Flight->class = $Flight->convert_name($request->input('name'));
        }

        $Flight->detail = $request->input('detail');
        

        // Kiểm tra nếu có file ảnh mới
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $fileName);
            $Flight->image = '/uploads/' . $fileName;
        }



        $Flight->save();

          // 🟢 Cập nhật categories nếu có gửi từ frontend
    if ($request->has('categories')) {
        $categoryIds = $request->input('categories'); // array: [1,2,...]
        $Flight->categories()->sync($categoryIds);
    }

        // $Flight->categories()->sync([2,1]); // gán category id 1 cố định

        return response()->json(['message' => 'Cập nhật thành công', 'image' => $Flight->image]);
    }

    // public function update(Request $request, $id) {
    //     $Flight = Flight::find($id);
    //     if ($Flight) {
    //         $Flight->name = $request->input('name');
    //         $Flight->class = $request->input('class');
    //         // $Flight->image = $request->file('image');
    //         $Flight->save();
    //         return response()->json(['message' => 'Cập nhật thành công']);
    //     }
    //     return response()->json(['message' => 'Không tìm thấy sản phẩm'], 404);
    // }

    public function add(Request $request)
    {
        $user = new Flight();
        $user->name = $request->input('name');
        if($request->input('class') != null)
        {
        $user->class = $request->input('class');
        }
        else
        {
        $user->class = $user->convert_name($request->input('name'));
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $fileName);
            $user->image = '/uploads/' . $fileName;
        }
        $user->detail = $request->input('detail');
        
        $user->save();
        
         // Lấy danh sách category_ids từ request và gán vào sản phẩm
    if ($request->has('categories')) {
        $categoryIds = $request->input('categories');
        $user->categories()->attach($categoryIds); // Gán các category cho sản phẩm
    }

       // Gán các category cố định (ví dụ: categories có id = 1, 2, 3)
       $categoryIds = [1,2]; 
        // Đây là giá trị cố định bạn muốn gán
       $user->categories()->attach($categoryIds); // Gán các category cho sản phẩm

        return response()->json($user);
    }

    public function sort(Request $request)
    {
        $key = $request->input('keyword', '');
        $flights = empty($key) ? Flight::all() : Flight::where('name', 'like', "%$key%")->get();
        return response()->json($flights);
    }
}
