<?php

namespace App\Http\Controllers\WEB\Admin;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();

        $activeUsers = Schema::hasColumn('users', 'status')
            ? User::where('status', 'active')->count()
            : $totalUsers;

        $visitors = Schema::hasColumn('users', 'visits')
            ? User::sum('visits')
            : 0;

        $referralUsers = Schema::hasColumn('users', 'referred_by')
            ? User::whereNotNull('referred_by')->count()
            : 0;

        return view('dashboard', compact('totalUsers', 'activeUsers', 'visitors', 'referralUsers'));
    }

    public function userGrowth(Request $request)
    {
        $period = $request->period; // week, month, year
        $data = ['labels' => [], 'users' => []];

        if ($period == 'week') {
            $start = Carbon::now()->startOfWeek();
            for ($i = 0; $i < 7; $i++) {
                $date = $start->copy()->addDays($i);
                $data['labels'][] = $date->format('D');
                $data['users'][] = User::whereDate('created_at', $date)->count();
            }
        } elseif ($period == 'month') {
            $start = Carbon::now()->startOfMonth();
            $days = Carbon::now()->daysInMonth;
            for ($i = 0; $i < $days; $i++) {
                $date = $start->copy()->addDays($i);
                $data['labels'][] = $date->format('d');
                $data['users'][] = User::whereDate('created_at', $date)->count();
            }
        } elseif ($period == 'year') {
            for ($i = 1; $i <= 12; $i++) {
                $data['labels'][] = Carbon::create()->month($i)->format('M');
                $data['users'][] = User::whereMonth('created_at', $i)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->count();
            }
        }

        return response()->json($data);
    }
}
