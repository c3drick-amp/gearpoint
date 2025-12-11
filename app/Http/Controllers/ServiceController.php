<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $services = Service::when($search, function($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('services', compact('services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|unique:services,code|max:255',
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'labor_fee' => 'required|numeric|min:0',
            'estimated_duration' => 'nullable|string|max:255',
        ]);

        Service::create($validated);

        return redirect()->route('services')->with('success', 'Service added successfully!');
    }

    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return redirect()->route('services')->with('success', 'Service deleted successfully!');
    }
}