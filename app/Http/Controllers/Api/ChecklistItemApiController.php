<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Checklist;
use App\Models\ChecklistItem;
use Illuminate\Http\Request;

class ChecklistItemApiController extends Controller
{
    public function index(Checklist $checklist)
    {
        return response()->json($checklist->items);
    }

    public function store(Request $request, Checklist $checklist)
    {
        $request->validate([
            'itemName' => 'required|string|max:255',
        ]);

        $item = $checklist->items()->create([
            'name' => $request->itemName,
            'is_checked' => false,
        ]);

        return response()->json($item, 201);
    }

    public function show(Checklist $checklist, ChecklistItem $item)
    {
        if ($item->checklist_id !== $checklist->id) {
            return response()->json(['error' => 'Item not in checklist'], 404);
        }

        return response()->json($item);
    }

    public function update(Request $request, Checklist $checklist, ChecklistItem $item)
    {
        if ($item->checklist_id !== $checklist->id) {
            return response()->json(['error' => 'Item not in checklist'], 404);
        }

        $request->validate([
            'itemName' => 'string|max:255',
            'is_checked' => 'boolean',
        ]);

        $item->update([
            'name' => $request->itemName ?? $item->name,
            'is_checked' => $request->has('is_checked') ? $request->is_checked : $item->is_checked,
        ]);

        return response()->json($item);
    }

    public function rename(Request $request, Checklist $checklist, ChecklistItem $item)
    {
        if ($item->checklist_id !== $checklist->id) {
            return response()->json(['error' => 'Item not found in checklist'], 404);
        }

        $request->validate([
            'itemName' => 'required|string|max:255',
        ]);

        $item->name = $request->itemName;
        $item->save();

        return response()->json(['message' => 'Item renamed', 'item' => $item]);
    }

    public function updateStatus(Request $request, Checklist $checklist, ChecklistItem $item)
    {
        if ($item->checklist_id !== $checklist->id) {
            return response()->json(['error' => 'Item not found in checklist'], 404);
        }

        $request->validate([
            'is_checked' => 'required|boolean',
        ]);

        $item->is_checked = $request->is_checked;
        $item->save();

        return response()->json(['message' => 'Status updated', 'item' => $item]);
    }

    public function destroy(Checklist $checklist, ChecklistItem $item)
    {
        if ($item->checklist_id !== $checklist->id) {
            return response()->json(['error' => 'Item not in checklist'], 404);
        }

        $item->delete();

        return response()->json(['message' => 'Item deleted']);
    }
}
