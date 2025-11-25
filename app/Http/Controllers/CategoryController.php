<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;
use App\Http\Controllers\Validator;
use Carbon\Carbon;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->input('sort', 'catid'); // default sort column
        $order = $request->input('order', 'asc');   // default order

        $allowedSorts = ['catid', 'cattype'];
        if (!in_array($sort, $allowedSorts)) {
            $sort = 'catid'; // default sort column
        }

        $categories = Categories::orderBy($sort, $order)->paginate(5);
        return view('admin.categoryManage', ['categories' => $categories]);
        // return redirect()->route('admin.dashboard', ['page' => 'categoryManage']);
    }

    public function addCategory(Request $request)
    {
        $request->validate([
            'catname' => 'required|string|unique:categories,catname|max:50',
            'catimage' => 'required|image|mimes:jpeg,png,jpg|max:10000',
            'catdesc' => 'nullable|string|max:200',
            'cattype' => 'required|integer|max:2',
        ], [
            'catname.required' => 'The name field is required.',
            'catname.string' => 'The name must be a string.',
            'catname.unique' => 'Category already exists.',
            'catname.max' => 'The name must be less than 50 characters.',
            'catimage.required' => 'The image field is required.',
            'catimage.mimes' => 'The image must be type of jpeg, jpg or png.',
            'catimage.max' => 'The image size less then 10000.',
            'catdesc.string' => 'The description must be a string.',
            'catdesc.max' => 'The description must be less than 200 characters.',
            'cattype.required' => 'The type field is required.',
            'cattype.integer' => 'The type must be a integer.',
        ]);

        $imageName = time() . '.' . $request->catimage->extension();
        $publicPath = public_path('catimages'); // Default Laravel public folder
        $extraPath = '/Users/dhrumilmandaviya/Dhrumil Iphone/MCA SEM - 3/306-SP-3/pizzahub/assets/catimages';

        // ✅ Move to public folder first
        $request->catimage->move($publicPath, $imageName);

        // ✅ Copy to extra path (create folder if it doesn’t exist)
        if (!file_exists($extraPath)) {
            mkdir($extraPath, 0777, true);
        }
        copy($publicPath . '/' . $imageName, $extraPath . '/' . $imageName);

        // Check if the request has 'iscombo' and set it to 0 if not present
        if (!$request->has('iscombo')) {
            $iscombo = 0;
        } else {
            $iscombo = $request->iscombo;
            $comboprice = $request->comboprice;
            $discount = $request->discount;
        }

        $category = Categories::create([
            'catname' => $request->catname,
            'catimage' => $imageName ?? null,
            'catdesc' => $request->catdesc,
            'cattype' => $request->cattype,
            'iscombo' => $iscombo,
            'comboprice' => $comboprice ?? 0,
            'discount' => $discount ?? 0,
            'catcreatedate' => Carbon::now('Asia/Kolkata'),
            'catupdatedate' => Carbon::now('Asia/Kolkata'),
        ]);

        return back()->with('success', 'Category added successfully!');
    }

    public function updateImage(Request $request, $catid)
    {
        $request->validate([
            'catimagee' => 'required|image|mimes:jpeg,png,jpg|max:10000'
        ], [
            'catimagee.required' => 'The image field is required.',
            'catimagee.mimes' => 'The image must be type of jpeg, jpg or png.',
            'catimagee.max' => 'The image size less then 10000.',
        ]);

        $category = Categories::where('catid', $catid)->first();

        if (isset($request->catimagee)) {
            $imageName = time() . '.' . $request->catimagee->extension();
            $publicPath = public_path('catimages'); // Default Laravel public folder
            $extraPath = '/Applications/XAMPP/xamppfiles/htdocs/PizzaHubApp/catimages';

            // ✅ Move to public folder first
            $request->catimagee->move($publicPath, $imageName);

            // ✅ Copy to extra path (create folder if it doesn’t exist)
            if (!file_exists($extraPath)) {
                mkdir($extraPath, 0777, true);
            }
            copy($publicPath . '/' . $imageName, $extraPath . '/' . $imageName);
        }

        $category->save();
        return back()->with('success', 'Image updated successfully!');
    }

    public function updateCategory(Request $request, $catid)
    {
        $request->validate([
            'catnamee' => 'required|string|max:50',
            'catdesce' => 'nullable|string|max:200',
        ], [
            'catname.required' => 'The name field is required.',
            'catname.string' => 'The name must be a string.',
            'catname.unique' => 'Category already exists.',
            'catname.max' => 'The name must be less than 50 characters.',
            'catdesc.text' => 'The description must be a text.',
            'catdesc.max' => 'The description must be less than 200 characters.',
        ]);

        $category = Categories::where('catid', $catid)->first();

        $category->catname = $request->catnamee;
        $category->catdesc = $request->catdesce;
        $category->catupdatedate = Carbon::now('Asia/Kolkata');

        $category->save();
        return back()->with('success', 'Category updated successfully!');
    }

    public function destroyCategory($catid)
    {
        $category = Categories::where('catid', $catid)->first();
        $category->delete();
        return back()->withSuccess('Category Removed Successfully!');
    }

    /******************************   User side   ******************************/

    public function userIndex(Request $request)
    {
        $categoryId = $request->category_id;
        $categories = collect();

        if ($categoryId == 0) {
            $categories = Categories::orderBy('iscombo', 'asc')->get(); // Show all categories
        } elseif ($categoryId == 3) {
            $categories = Categories::where('iscombo', 1)->get();
        } else {
            $categories = Categories::where('cattype', $categoryId)->where('iscombo', 0)->get(); // Filter based on selected category
        }

        if ($categories->isEmpty()) {
            return response()->json([
                'message' => 'No categories found for the selected filter.'
            ]);
        }

        // if ($categories->isEmpty()) {
        //     $categories = "No categories found for the selected filter.";
        //     return view('index', ['message' => $categories]);
        // }

        return view('index', ['categories' => $categories]);
    }
}
