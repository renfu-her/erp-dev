<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePositionRequest;
use App\Http\Requests\UpdatePositionRequest;
use App\Http\Resources\PositionResource;
use App\Models\Position;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PositionController extends Controller
{
    public function index(Request $request): ResourceCollection
    {
        $this->authorizePositionManage($request);

        $positions = Position::query()
            ->with('department:id,name,company_id')
            ->when($request->filled('department_id'), fn ($query) => $query->where('department_id', $request->integer('department_id')))
            ->orderBy('title')
            ->paginate(perPage: $request->integer('per_page', 20));

        return PositionResource::collection($positions);
    }

    public function store(StorePositionRequest $request): PositionResource
    {
        $position = Position::create($request->validated());

        return PositionResource::make($position);
    }

    public function show(Request $request, Position $position): PositionResource
    {
        $this->authorizePositionManage($request);

        return PositionResource::make($position->load('department:id,name'));
    }

    public function update(UpdatePositionRequest $request, Position $position): PositionResource
    {
        $position->update($request->validated());

        return PositionResource::make($position);
    }

    public function destroy(Request $request, Position $position): JsonResponse
    {
        $this->authorizePositionManage($request);

        $position->delete();

        return response()->json(['message' => 'Position removed']);
    }

    protected function authorizePositionManage(Request $request): void
    {
        abort_unless(
            $request->user()?->hasPermission('position.manage'),
            403,
        );
    }
}
