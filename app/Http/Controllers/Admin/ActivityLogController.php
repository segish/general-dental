<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use App\CentralLogics\Helpers;
use Illuminate\Contracts\View\View;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
   
    public function __construct(
        private Activity $activity,
    ){ 
     $this->middleware('checkAdminPermission:business-settings.activity,activity')->only(['activity']);

}

public function activity(Request $request): Factory|View|Application
{
    $query_param = [];
    $search = $request->input('search');
    
    $query = $this->activity->when($search, function ($q) use ($search) {
        $q->where('id', 'like', "%{$search}%")
          ->orWhere('description', 'like', "%{$search}%")
          ->orWhereHas('causer', function ($subQuery) use ($search) {
              $subQuery->where('f_name', 'like', "%{$search}%")
                        ->orWhere('l_name', 'like', "%{$search}%")
                        ->orWhereRaw("CONCAT_WS(' ', COALESCE(f_name, ''), COALESCE(l_name, '')) like ?", ["%{$search}%"]);
          });
    })->latest();

    $query_param['search'] = $search;

    $activities = $query->paginate(Helpers::pagination_limit())->appends($query_param);

    return view('admin-views.activity.list', compact('activities', 'search'));
}


    public function detail($id)
    {
        $activity = Activity::with('causer')->findOrFail($id);
        return view('admin-views.activity.detail', compact('activity'));

      
    }
}
