<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\School;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'log_name', 'school_id', 'date']);

        $query = ActivityLog::with(['user', 'school'])->latest();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhere('log_name', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('school', function ($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if (!empty($filters['log_name'])) {
            $query->where('log_name', $filters['log_name']);
        }

        if (!empty($filters['school_id'])) {
            $query->where('school_id', $filters['school_id']);
        }

        if (!empty($filters['date'])) {
            $query->whereDate('created_at', $filters['date']);
        }

        $logs = $query->paginate(15)->withQueryString();
        $schools = School::orderBy('name')->get();
        $logModules = ActivityLog::select('log_name')->distinct()->whereNotNull('log_name')->pluck('log_name');

        return view('superadmin.audit.index', compact('logs', 'filters', 'schools', 'logModules'));
    }
}
