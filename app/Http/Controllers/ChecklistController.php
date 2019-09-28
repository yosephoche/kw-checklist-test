<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

use Illuminate\Support\Carbon;
use App\Checklist;
use App\Task;
use App\JsonResponses\Resource\ChecklistStore as ChecklistStoreResource;
use App\JsonResponses\Resource\Checklist as ChecklistResource;
use App\JsonResponses\ResourceCollection\Checklist as ChecklistCollection;
use Illuminate\Support\Facades\Response;

class ChecklistController extends Controller
{
    public function index(Request $request)
    {
        $checklists = Checklist::jsonPaginate(
                $request,
                env('APP_URL') . ":8000/v1/checklists"
            );
        return new ChecklistCollection($checklists);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'object_domain' => 'required|string',
            'object_id' => 'required|integer',
            'description' => 'required|string',
            'urgency' => 'integer',
        ]);

        try {
            $data = $request->input();
            $data['due'] = Carbon::parse($data['due']);
            $data['created_by'] = $request->user()->id;
            $data['task_id'] = Task::create()->id;

            $created = Checklist::create($data);

            return new ChecklistStoreResource($created);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show(Request $request, $checklistId)
    {
        $record = Checklist::findOrFail($checklistId);

        return new ChecklistResource($record);
    }

    public function update(Request $request, $checklistId)
    {
        $this->validate($request, [
            'object_domain' => 'required|string',
            'object_id' => 'required|integer',
            'description' => 'required|string',
            'urgency' => 'integer',
        ]);

        try {
            $data = $request->input();
            $data['created_at'] = Carbon::parse($data['created_at']);
            $data['updated_by'] = $request->user()->id;

            $updated = Checklist::findOrFail($checklistId);

            $updated->update($data);

            return new ChecklistResource($updated->fresh());

        } catch (\Exception $e) {
            return response(['status' => '500', 'error' => $e->getMessage()], 500);
        }

    }

    public function destroy(Request $request, $checklistId)
    {
        $destroy = Checklist::findOrFail($checklistId);
        $destroy->delete();

        return response('The 204 response', 204);
    }
}
