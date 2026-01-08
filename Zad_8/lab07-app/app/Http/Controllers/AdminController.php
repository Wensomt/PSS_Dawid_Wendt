<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdminController extends Controller
{
    /**
     * Wyświetl panel admina ze statystykami
     */
    public function index(): View
    {
        $usersCount = User::count();
        $adminsCount = User::where('is_admin', true)->count();

        return view('admin.index', [
            'total_users' => $usersCount,
            'total_admins' => $adminsCount,
        ]);
    }

    /**
     * Wyświetl listę wszystkich użytkowników
     */
    public function listUsers(): View
    {
        $users = User::all();
        return view('admin.users', ['users' => $users]);
    }

    /**
     * Przełącz status admina dla użytkownika
     */
    public function toggleAdmin(User $user): RedirectResponse
    {
        if (auth()->id() === $user->id) {
            return redirect()
                ->route('admin.users')
                ->withErrors('Nie możesz odebrać sobie uprawnień administratora.');
        }

        $user->update(['is_admin' => !$user->is_admin]);

        $status = $user->is_admin ? 'nadane' : 'usunięte';

        return redirect()->route('admin.users')
            ->with('success',
                'Uprawnienia dla użytkownika ' . $user->name . ' zostały ' . $status . '.');
    }
}
