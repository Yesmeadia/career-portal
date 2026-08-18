<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApplicationApiController extends Controller
{
    public function track(Request $request): JsonResponse
    {
        $request->validate([
            'reference_no' => 'required|string',
        ]);

        $application = Application::where('reference_no', trim($request->reference_no))->with(['vacancy.school'])->first();

        if (!$application) {
            return response()->json(['status' => 'error', 'message' => 'Application reference number not found.'], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'reference_no' => $application->reference_no,
                'candidate_name' => $application->full_name,
                'job_title' => $application->vacancy->title,
                'school_name' => $application->school->name,
                'status' => $application->status,
                'applied_at' => $application->created_at->format('Y-m-d H:i:s'),
            ],
        ]);
    }
}
