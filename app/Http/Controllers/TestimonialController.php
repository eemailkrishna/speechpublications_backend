<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Contact;
use App\Models\Testimonial;
use Mail;
use validated;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\TestimonialResource;


class TestimonialController extends Controller
{
    public function index(){
        $testimonials = Testimonial::latest()->get();
        return view('admin.Testimonial.list', compact('testimonials'));
    }
    
    public function testimonial_add(){
        return view('admin.Testimonial.create');
    }
    
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'designation' => 'required',
        'description' => 'required',
        'image' => 'required'
    ]);

    $imageName = null;

    if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = uniqid('', true).'.'.$file->getClientOriginalExtension();
            Storage::disk('s3')->putFileAs(
                'testimonials',        // folder
                $file,
                $fileName
            );
            $imageName = $fileName;
    }
    

    Testimonial::create([
        'name' => $request->name,
        'designation' => $request->designation,
        'description' => $request->description,
        'image' => $imageName
    ]);

    return response()->json([
        'status' => true,
        'message' => 'Testimonial created successfully!'
    ]);
}

public function edit($id)
{
    $testimonial = Testimonial::findOrFail($id);
    return view('admin.Testimonial.edit', compact('testimonial'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required',
        'designation' => 'required',
        'description' => 'required',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
    ]);

    $testimonial = Testimonial::findOrFail($id);

    // Update image if selected
    if ($request->hasFile('image')) {
        
        if (!empty($testimonial->image)) {
                    $oldPath = 'testimonials/' . $testimonial->image;
                    if (Storage::disk('s3')->exists($oldPath)) {
                        Storage::disk('s3')->delete($oldPath);
                    }
            }
            $file = $request->file('image');
            $fileName = uniqid('', true).'.'.$file->getClientOriginalExtension();
            Storage::disk('s3')->putFileAs(
                'testimonials',        // folder
                $file,
                $fileName
            );

        $testimonial->image = $fileName;
    }

    $testimonial->name = $request->name;
    $testimonial->designation = $request->designation;
    $testimonial->description = $request->description;

    $testimonial->save();

    return redirect()->route('testimonial.list')->with('success', 'Updated Successfully');
}

public function destroy($id)
{
    $data = Testimonial::findOrFail($id);

    if ($data->image && file_exists(public_path('uploads/testimonials/' . $data->image))) {
        unlink(public_path('uploads/testimonials/' . $data->image));
    }

    $data->delete();

    return response()->json(['status' => true, 'message' => 'Deleted successfully']);
}


    
}