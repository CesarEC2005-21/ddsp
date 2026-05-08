<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AuditLog;
use App\Models\AccessLog;

class SecurityController extends Controller
{
    public function audit()
    {
        $logs = AuditLog::with('user')->latest()->paginate(15);
        return view('admin.security.audit', compact('logs'));
    }

    public function access()
    {
        $logs = AccessLog::with('user')
            ->whereHas('user', function($q) {
                $q->where('role', '!=', 'ing_sistemas');
            })
            ->latest()
            ->paginate(15);
            
        return view('admin.security.access', compact('logs'));
    }
}
