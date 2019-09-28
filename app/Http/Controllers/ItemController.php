<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Carbon;

use App\Checklist;
use App\Item;
use App\JsonResponses\Resource\ChecklistItem as ChecklistItemResource;
use App\JsonResponses\Resource\ItemStatus as ItemStatusResource;
use App\JsonResponses\Resource\ChecklistItemSingle as ChecklistItemSingleResource;
use App\JsonResponses\ResourceCollection\ChecklistItem as ChecklistItemCollection;

class ItemController extends Controller
{
    public function __construct()
    {

    }

    public function index(Request $request, $checklistId)
    {
        $items = Item::with('checklist')->where('checklist_id', $checklistId)->jsonPaginate(
                $request,
                env('APP_URL') . ":8000/v1/checklists/{$checklistId}/items"
            );

        return new ChecklistItemCollection($items);
    }

    public function store(Request $request, $checklistId)
    {
        $this->validate($request, [
            'description' => 'required|string',
            'urgency' => 'integer',
            'assignee_id' => 'integer'
        ]);

        $checklist = Checklist::findOrFail($checklistId);

        $data = $request->input();
        $data['created_by'] = $request->user()->id;
        $data['user_id'] = $request->user()->id;
        $data['task_id'] = $checklist->task_id;
        $data['due'] = Carbon::parse($data['due']);

        $created = $checklist->items()->create($data);

        return new ChecklistItemResource($created);
    }

    public function show(Request $request, $checklistId, $itemId)
    {
        $record = Checklist::findOrFail($checklistId)->items()->findOrFail($itemId);

        return new ChecklistItemSingleResource($record);
    }

    public function update(Request $request, $checklistId, $itemId)
    {
        $this->validate($request, [
            'description' => 'required|string',
            'urgency' => 'integer',
            'assignee_id' => 'integer'
        ]);

        try {
            $data = $request->input();
            $data['updated_by'] = $request->user()->id;
            $data['due'] = Carbon::parse($data['due']);

            $updated = Checklist::findOrFail($checklistId)->items()->findOrFail($itemId);
            $updated->update($data);

            return new ChecklistItemResource($updated->fresh());
        } catch (\Exception $e) {
            return response(['status' => '500', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy(Request $request, $checklistId, $itemId)
    {
        $destroy = Item::findOrFail($itemId);
        $destroy->delete();

        return response('', 204);
    }

    public function complete(Request $request)
    {
        $this->validate($request, [
            '*.item_id' => 'required|integer',
        ]);

        $itemIds = collect($request->input())->pluck('item_id');
        $items = Item::where('is_completed', true)->whereIn('id', $itemIds)->get();

        return ItemStatusResource::collection($items);
    }

    public function incomplete(Request $request)
    {
        $this->validate($request, [
            '*.item_id' => 'required|integer',
        ]);

        $itemIds = collect($request->input())->pluck('item_id');
        $items = Item::where('is_completed', false)->whereIn('id', $itemIds)->get();

        return ItemStatusResource::collection($items);
    }

    public function summaries(Request $request)
    {

    }

    public function updateBulk(Request $request)
    {

    }
}
