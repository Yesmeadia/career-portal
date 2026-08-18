<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\GlobalClass;
use Illuminate\Http\Request;

class GlobalClassController extends Controller
{
    public function index()
    {
        $classes = GlobalClass::orderBy('sort_order')->paginate(15);
        return view('superadmin.classes.index', compact('classes'));
    }

    public function show($id = null)
    {
        return redirect()->route('superadmin.global-classes.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer',
        ]);

        GlobalClass::create($validated);
        return redirect()->back()->with('success', 'Global Class added successfully.');
    }

    public function update(Request $request, $id)
    {
        $class = GlobalClass::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $class->update($validated);
        return redirect()->back()->with('success', 'Global Class updated successfully.');
    }

    public function destroy($id)
    {
        $class = GlobalClass::find($id);
        if ($class) {
            $name = $class->name;
            $class->delete();
            return redirect()->back()->with('success', "Global Class '{$name}' removed successfully.");
        }
        return redirect()->back()->with('info', 'Global Class already removed.');
    }
}
