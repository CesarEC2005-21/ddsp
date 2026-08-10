<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    private $sectionFields = [
        'inicio'    => ['image_path', 'hero_image_2', 'hero_image_3', 'gallery_image_1', 'gallery_image_2', 'gallery_image_3'],
        'nosotros'  => ['image_path', 'historia_image', 'historia_2023_image', 'historia_2024_image', 'historia_2025_image', 'historia_2022_image', 'mision_image', 'vision_image'],
        'novedades'  => ['image_path'],
        'ejecutivos'=> ['image_path'],
        'productos' => ['image_path'],
        'contacto'  => ['image_path'],
    ];

    public function index()
    {
        $banners = Banner::all()->keyBy('section');
        $sectionFields = $this->sectionFields;
        return view('admin.banners.index', compact('banners', 'sectionFields'));
    }

    public function update(Request $request, Banner $banner)
    {
        $fields = $this->sectionFields[$banner->section] ?? ['image_path'];

        $rules = [];
        foreach ($fields as $field) {
            $rules[$field] = 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120';
        }
        $request->validate($rules);

        foreach ($fields as $field) {
            if ($request->hasFile($field)) {
                if ($banner->$field && Storage::disk('public')->exists($banner->$field)) {
                    Storage::disk('public')->delete($banner->$field);
                }
                $path = $request->file($field)->store('banners', 'public');
                $banner->$field = $path;
            }
        }
        $banner->save();

        return redirect()->back()->with('success', 'Banners actualizados correctamente para la sección: ' . ucfirst($banner->section));
    }
}
