<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AuditLog;
use App\Models\AccessLog;

class SecurityController extends Controller
{
    public function audit(Request $request)
    {
        $query = AuditLog::with('user');
        
        if ($request->filled('usuario')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->usuario . '%');
            });
        }
        if ($request->filled('accion')) {
            $query->where('action', $request->accion);
        }
        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }
        
        $logs = $query->latest()->paginate(15);
        return view('admin.security.audit', compact('logs'));
    }

    public function access(Request $request)
    {
        $query = AccessLog::with('user')
            ->whereHas('user', function($q) {
                $q->where('role', '!=', 'ing_sistemas');
            });
            
        if ($request->filled('usuario')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->usuario . '%');
            });
        }
        if ($request->filled('fecha_desde')) {
            $query->whereDate('login_at', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('login_at', '<=', $request->fecha_hasta);
        }
            
        $logs = $query->latest()->paginate(15);
            
        return view('admin.security.access', compact('logs'));
    }
}
