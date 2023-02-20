<?php

namespace App\Http\Controllers\API;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use GuzzleHttp\Psr7\Response;

class CompanyController extends Controller
{
    // /api/company?id=1
    public function all(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $limit = $request->input('limit', 10);

        if ($id) {
            $company = Company::with(['users'])->find($id);

            if ($company) {
                return ResponseFormatter::success($company);
            }

            return ResponseFormatter::error('Company not Found');
        }

        $companies = Company::with(['users']);

        if ($name) {
            $companies->where('name', 'like', '%' . $name . '%');
        }

        return ResponseFormatter::success(
            $companies->paginate($limit),
            'companies found'
        );
    }
}
