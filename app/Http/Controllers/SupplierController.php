<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Material;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\Laravel\Facades\Image;


class SupplierController extends Controller
{
    public function index()
    {
        return view('supplier.index');
    }

    public function materials()
    {
        $materials = Material::orderBy('id', 'DESC')->paginate(10);
        return view('supplier.materials', compact('materials'));
    }

    public function materials_add()
    {
        return view('supplier.material-add');
    }

    public function materials_store(Request $request)
    {
        $request->validate([
            'image' => 'required|mimes:png,jpg,jpeg|max:2048',
            'name' => 'required',
            'description' => 'required',
            'quantity' => 'required',
        ]);

        $materials = new Material();
        $materials->name = $request->name;
        $materials->description = $request->description;
        $materials->quantity = $request->quantity;
        $image = $request->file('image');
        $file_extension = $image->extension();
        $file_name = Carbon::now()->timestamp . '.' . $file_extension;
        $this->GenerateMaterialThumbnailImage($image, $file_name);
        $materials->image = $file_name;
        $materials->save();
        return redirect()->route('supplier.materials')->with('status', 'Material has been added successfully');
    }

    public function GenerateMaterialThumbnailImage($image, $imageName)
    {
        $destinationPath = public_path('uploads/materials');
        $img = Image::read($image->path());
        $img->cover(150, 150, "top");
        $img->resize(150, 150, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath . '/' . $imageName);
    }

    public function materials_edit($id)
    {
        $materials = Material::find($id);
        return view('supplier.material-edit', compact('materials'));
    }

    public function materials_update(Request $request)
    {
        $request->validate([
            'image' => 'mimes:png,jpg,jpeg|max:2048',
            'name' => 'required',
            'description' => 'required',
            'quantity' => 'required',

        ]);

        $materials = Material::find($request->id);
        $materials->name = $request->name;
        $materials->description = $request->description;
        $materials->quantity = $request->quantity;
        if ($request->hasFile('image')) {
            if (File::exists(public_path('uploads/materials') . '/' . $materials->image)) {
                File::delete(public_path('uploads/materials') . '/' . $materials->image);
            }
            $image = $request->file('image');
            $file_extension = $image->extension();
            $file_name = Carbon::now()->timestamp . '.' . $file_extension;
            $this->GenerateMaterialThumbnailImage($image, $file_name);
            $materials->image = $file_name;
        }
        $materials->save();
        return redirect()->route('supplier.materials')->with('status', 'Material has been updated successfully');
    }


    public function materials_delete($id)
    {
        $materials = Material::find($id);
        if (File::exists(public_path('uploads/materials') . '/' . $materials->image)) {
            File::delete(public_path('uploads/materials') . '/' . $materials->image);
        }
        $materials->delete();
        return redirect()->route('supplier.materials')->with('status', 'Material has been deleted successfully');
    }


    // public function suppliers()
    // {
    //     $suppliers = Supplier::orderBy('created_at', 'DESC')->paginate(10);
    //     return view('supplier.suppliers', compact('suppliers'));
    // }

    // public function suppliers_add()
    // {
    //     return view('supplier.suppliers-add');
    // }

    // public function suppliers_store(Request $request)
    // {
    //     // Validasi input
    //     $validatedData = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|email|unique:users,email',
    //         'phone' => 'required|numeric|digits_between:10,15',
    //     ]);

    //     // Buat user baru
    //     $user = Supplier::create([
    //         'name' => $validatedData['name'],
    //         'email' => $validatedData['email'],
    //         'phone' => $validatedData['phone'],
    //     ]);

    //     return redirect()->route('supplier.suppliers')->with('status', 'Supplier has been added successfully!');
    // }

    // public function suppliers_edit($id)
    // {
    //     $suppliers = Supplier::find($id);
    //     return view('supplier.suppliers-edit', compact('suppliers'));
    // }

    // public function suppliers_update(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|email|unique:users,email',
    //         'phone' => 'required|numeric|digits_between:10,15'
    //     ]);

    //     $suppliers = Supplier::find($request->id);
    //     $suppliers->name = $request->name;
    //     $suppliers->email = $request->email;
    //     $suppliers->phone = $request->phone;
    //     $suppliers->save();
    //     return redirect()->route('supplier.suppliers')->with('status', 'Supplier has been updated successfully!');
    // }

    // public function users_delete($id)
    // {
    //     $suppliers = Supplier::find($id);
    //     $suppliers->delete();
    //     return redirect()->route('supplier.suppliers')->with('status', 'Supplier has been deleted successfully!');
    // }
}
