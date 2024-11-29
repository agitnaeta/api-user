<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserInputRequest;
use App\Http\Requests\UserSearchRequest;
use App\Mail\NewUserRegister;
use App\Mail\UserConfirmRegister;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(UserSearchRequest $request)
    {
        // Build the query to retrieve users with orders count and apply search filter
        $users = User::isActive()->withCount('orders')
            ->where(function ($q) use ($request) {
                if ($request->search) {
                    $q->where("name", "like", "%{$request->search}%")
                        ->orWhere("email", "like", "%{$request->search}%");
                }
            });

        // Apply sorting based on the sortBy parameter
        $sortBy = $request->sortBy ?? 'created_at';
        if($request->sortBy){
            $users = $users->orderBy($sortBy, 'asc');
        }

        // Paginate the results
        $usersPaginated = $users->paginate(5);


        return response()->json([
            "page" => $usersPaginated->currentPage(),
            "users" => $usersPaginated->items(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserInputRequest $request)
    {
        $user = User::create($request->validated());
        $this->sendingNotificationEmail($user);
        return response()->json($user);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    /**
     * Sending email using queue, so it's not blocking API request
     * @param User $user
     * @return void
     */
    private function sendingNotificationEmail(User $user){
        // Sending to user
        Mail::to($user->email)->queue(new UserConfirmRegister($user));

        // Sending to administrator, let's say here we have multiple administrators
        $administratorEmails = User::isAdministrator()->pluck('email');
        foreach($administratorEmails as $administratorEmail){
            Mail::to($administratorEmail)->queue(new NewUserRegister($user));
        }
    }
}
