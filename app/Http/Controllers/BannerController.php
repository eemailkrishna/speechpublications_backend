<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class BannerController extends Controller
{
    public function index()
    {
        $banners= Banner::paginate(10);
        return view('admin.banner.list',['banners'=>$banners]);
    }
    
    public function create()
    {
        return view('admin.banner.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/banner/'), $filename);
        }
        
        Banner::create([
            'image' => $filename,
        ]);
        
        return back()->with('success', 'Banner added successfully!');
    }
    
    public function edit($id)
    {
        $banner = Banner::findOrFail($id);
        return view('admin.banner.edit',['banner'=>$banner]);
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $banner = Banner::findOrFail($id);
    
        if ($request->hasFile('image')) {
            $file=public_path('assets/images/banner/'.$banner->image);
    
            if($banner->image){
    
                if (file_exists($file)) {
                    if (unlink($file)) {
                        echo 'File deleted successfully.';
                    } else {
                        echo 'Failed to delete file.';
                    }
                } else {
                    echo 'File does not exist.';
                }
            }
            $file = $request->file('image');
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/banner/'), $filename);
        }
    
        $banner->update([
            'image' => $filename,
        ]);
    
        return back()->with('success', 'Banner updated successfully!');
    }
    
    
}
