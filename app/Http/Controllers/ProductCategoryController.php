<?php



namespace App\Http\Controllers;



use Illuminate\Http\Request;

use App\Models\ProductCategory;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Auth;
use Illuminate\Support\Facades\Storage;



class ProductCategoryController extends Controller

{

    public function index(){
       return view('admin.product-category.create');

    }

    public function show(){  
        $cateogry = ProductCategory::orderBy('asc_id')->get();
        return view('admin.product-category.list',['categories'=>$cateogry]);
 
     }

    public function store(Request $request){
        $request->validate([
            "name"=>'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $imageName=null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = uniqid('', true).'.'.$file->getClientOriginalExtension();
            Storage::disk('s3')->putFileAs(
                'product-category',        // folder
                $file,
                $fileName
            );
            $imageName = $fileName;
     }
        
        $slug = Str::slug($request->name, '-');

        $asc_id = ProductCategory::orderBy('asc_id', 'desc')->first();
       
        
        ProductCategory::create([
            "name"=> $request->name,
            "slug"=>$slug,
            "image" => $imageName,
            "asc_id"=> $asc_id!=null?$asc_id->asc_id+1:1
        ]);
        
        return back()->with('success', 'Product Category added successfully!');
 
     }

     public function delete_category($id){

        $category = ProductCategory::find($id);
        if( $category->image){                
                    $path = public_path('images/product-category/' . $category->image);
                    if (file_exists($path)) {
                        unlink($path); 
                    }
                }
          
        
       
        $is_deleted = $category->delete();
        if($is_deleted){
            return back()->with('success','Product category deleted successfully');

        }

     }
     public function edit($id)
    {
        $category = ProductCategory::findOrFail($id);
        return view('admin.product-category.edit',['category'=>$category]);
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $category = ProductCategory::findOrFail($id);

        $filename = null; // Default to existing image name
    
        if ($request->hasFile('image')) {
             if (!empty($category->image)) {
                    $oldPath = 'product-category/' . $category->image;
                    if (Storage::disk('s3')->exists($oldPath)) {
                        Storage::disk('s3')->delete($oldPath);
                    }
            }
            $file = $request->file('image');
            $fileName = uniqid('', true).'.'.$file->getClientOriginalExtension();
            Storage::disk('s3')->putFileAs(
                'product-category',        // folder
                $file,
                $fileName
            );
            $filename = $fileName;
        }
        else{
            $filename=$request->exit_image;
        }
        
    
        $category->update([
            'name'=>$request->name,
            'image' => $filename,
        ]);
    
        return back()->with('success', 'Product Category updated successfully!');
    }

    public function updateOrder(Request $request)
    {
        foreach ($request->order as $item) {
            ProductCategory::where('id', $item['id'])->update(['asc_id' => $item['position']]);
        }

        return response()->json(['message' => 'Order updated successfully']);
    }
    
     

}