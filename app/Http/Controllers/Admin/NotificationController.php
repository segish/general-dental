<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;

class NotificationController extends Controller
{
    public function __construct(
        private Notification $notification,
        private User $user,
    ){


    $this->middleware('checkAdminPermission:notification.add-new,index')->only(['index']);

    }



    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    function index(Request $request): Factory|View|Application
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $notifications = $this->notification->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('title', 'like', "%{$value}%")
                        ->orWhere('description', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        }else{
            $notifications = $this->notification;
        }

        $users = $this->user->all();



        $notifications = $notifications->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.notification.index', compact('notifications','address','users','categories','search'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:100',
            'description' => 'required',
        ], [
            'title.max' => 'Title must not be greater than 100 characters!',
            'description.required' => 'Description is required!',
        ]);
    
        if (!empty($request->file('image'))) {
            $image_name = Helpers::upload('notification/', 'png', $request->file('image'));
        } else {
            $image_name = null;
        }
    
        $notification = $this->notification;
        $notification->title = $request->title;
        $notification->description = $request->description;
        $notification->image = $image_name;
        $notification->status = 1;
        $notification->save();
    
        $categoryIds = $request->category_id;
        $allTokens = $request->users_cm_token;

        if(!empty($categoryIds)){
            $usersCMTokens = $this->getCMTokensOfUsersInCategories($categoryIds);
        }
    
        if (!empty($usersCMTokens) ) {
            if (!empty($request->users_cm_token)) {
                $mergeTokens = array_unique(array_merge($usersCMTokens, $request->users_cm_token));
                $allTokens = array_values($mergeTokens);
            }
            else{
                $allTokens=$usersCMTokens;
            }

        }
        
        if(!empty($allTokens)){
            try {
                Helpers::send_push_notif_to_group($allTokens, $notification);
            } catch (\Exception $e) {
                Toastr::warning(translate('Push notification failed!'));
            }
        }
        else{
            try {
                Helpers::send_push_notif_to_topic($notification, 'general');
            } catch (\Exception $e) {
                Toastr::warning(translate('Push notification failed!'));
            }
        }
    
        Toastr::success(translate('Notification sent successfully!'));
        return back();
    }
    
    private function getCMTokensOfUsersInCategories(array $categoryIds): array
    {
        $usersCMTokens = User::whereHas('customerCategories', function ($query) use ($categoryIds) {
            $query->whereIn('customer_category_id', $categoryIds);
        })->pluck('cm_firebase_token')->toArray();
    
        return $usersCMTokens;
    }
    
    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function edit($id): Factory|View|Application
    {
        $notification = $this->notification->find($id);
        return view('admin-views.notification.edit', compact('notification'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ], [
            'title.required' => 'title is required!',
        ]);

        $notification = $this->notification->find($id);
        $notification->title = $request->title;
        $notification->description = $request->description;
        $notification->image = $request->has('image') ? Helpers::update('notification/', $notification->image, 'png', $request->file('image')) : $notification->image;
        $notification->save();
        Toastr::success(translate('Notification updated successfully!'));
        return back();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function status(Request $request): RedirectResponse
    {
        $notification = $this->notification->find($request->id);
        $notification->status = $request->status;
        $notification->save();
        Toastr::success(translate('Notification status updated!'));
        return back();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete(Request $request): RedirectResponse
    {
        $notification = $this->notification->find($request->id);
        if (Storage::disk('public')->exists('notification/' . $notification['image'])) {
            Storage::disk('public')->delete('notification/' . $notification['image']);
        }
        $notification->delete();
        Toastr::success(translate('Notification removed!'));
        return back();
    }
}
