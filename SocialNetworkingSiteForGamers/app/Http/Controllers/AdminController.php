<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{

public function index(Request $request)
{
    $query = Game::where('visible', 1);

    if ($request->filled('search')) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    $games = $query->get();

    return view('admin.index', compact('games'));
}

public function storeGame(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:games,name',
        'image' => 'nullable|image|max:2048',
    ], [
        'name.unique' => 'Gra o takiej nazwie już istnieje!',
    ]);

    $imagePath = null;
    if ($request->hasFile('image')) {
        $imageName = uniqid().'_'.$request->file('image')->getClientOriginalName();
        $request->file('image')->move(public_path('IMG'), $imageName);
        $imagePath = 'IMG/' . $imageName;
    }

    Game::create([
        'name' => $request->name,
        'image' => $imagePath,
    ]);
    return redirect()->route('admin.index')->with('success', 'Gra została dodana!');
}

public function updateGame(Request $request)
{
    $request->validate([
        'game_id' => 'required|exists:games,id',
        'name' => 'nullable|string|max:255|unique:games,name,' . $request->game_id,
        'image' => 'nullable|image|max:2048',
    ]);
    $game = Game::findOrFail($request->game_id);

    
    if ($request->filled('name')) {
        $game->name = $request->name;
    }


    if ($request->hasFile('image')) {
        $imageName = uniqid().'_'.$request->file('image')->getClientOriginalName();
        $request->file('image')->move(public_path('IMG'), $imageName);
        $game->image = 'IMG/' . $imageName;
    }

    $game->save();
    return redirect()->route('admin.index')->with('success', 'Gra zaktualizowana!');
}
    public function storeUser(Request $request)
    {
        $request->validate([
    'username' => 'required|string|max:255|unique:users,username',
    'email' => 'required|email|max:255|unique:users,email',
    'password' => [
        'required',
        'string',
        'min:8',
        'regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/'
    ],
    'role' => 'required|in:user,admin',
], [
    'password.regex' => 'Hasło musi zawierać co najmniej jedną dużą literę, jedną cyfrę i jeden znak specjalny.',
]);
        User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password_hash' => Hash::make($request->password),
            'role' => $request->role,
        ]);
        return redirect()->route('admin.index')->with('success', 'Użytkownik dodany!');
    }


public function deleteGame(Request $request)
{
    $request->validate([
        'game_id' => 'required|exists:games,id',
    ]);
    $game = Game::findOrFail($request->game_id);

    $game->visible = 0;
    $game->save();

    return redirect()->route('admin.index')->with('success', 'Gra została ukryta!');
}

}