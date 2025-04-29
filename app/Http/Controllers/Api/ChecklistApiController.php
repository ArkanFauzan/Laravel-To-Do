<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Checklist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChecklistApiController extends Controller
{
    public function index()
    {
        return response()->json(Auth::user()->checklists);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $checklist = Checklist::create([
            'name' => $request->name,
            'user_id' => Auth::id(),
        ]);

        return response()->json($checklist, 201);
    }

    public function update(Request $request, $id)
    {

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $checklist = Checklist::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $checklist->update([
            'name' => $request->name,
        ]);

        return response()->json($checklist);
    }

    public function destroy($id)
    {
        $checklist = Checklist::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $checklist->delete();

        return response()->json(['message' => 'Checklist deleted']);
    }
}
