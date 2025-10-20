<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CompanyController extends Controller
{
    public function index(Request $request): ResourceCollection
    {
        $this->authorizeCompanyManage($request);

        $companies = Company::query()
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->orderBy('name')
            ->paginate(perPage: $request->integer('per_page', 15));

        return CompanyResource::collection($companies);
    }

    public function store(StoreCompanyRequest $request): CompanyResource
    {
        $company = Company::create($request->validated());

        return CompanyResource::make($company);
    }

    public function show(Request $request, Company $company): CompanyResource
    {
        $this->authorizeCompanyManage($request);

        return CompanyResource::make($company);
    }

    public function update(UpdateCompanyRequest $request, Company $company): CompanyResource
    {
        $company->update($request->validated());

        return CompanyResource::make($company);
    }

    public function destroy(Request $request, Company $company): JsonResponse
    {
        $this->authorizeCompanyManage($request);

        $company->delete();

        return response()->json(['message' => 'Company removed']);
    }

    protected function authorizeCompanyManage(Request $request): void
    {
        abort_unless(
            $request->user()?->hasPermission('company.manage'),
            403,
        );
    }
}
