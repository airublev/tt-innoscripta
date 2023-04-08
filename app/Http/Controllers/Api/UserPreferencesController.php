<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\UserPreference;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class UserPreferencesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $preferences = UserPreference::where('user_id', $user->id)->get();

        return response()->json($preferences);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->key == 'category') {
            $query = Category::query();
            $category = $query->where('id', '=', $request->value)->first();

            $user = Auth::user();

            $preference = [
                'key' => $category->name,
                'value' => $category->id
            ];

            $preference = new UserPreference($preference);
            $preference->user_id = $user->id;
            $preference->save();

            return response()->json(['message' => 'User preference created successfully', 'data' => $preference]);
        }

        return response(['message' => 'User preference created not successfully'], Response::HTTP_INTERNAL_SERVER_ERROR)->header('Content-Type', 'application/json');
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
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $preference = UserPreference::where('user_id', $user->id)->findOrFail($id);

        $validatedData = $request->validate([
            'key' => 'required|string',
            'value' => 'required|string',
        ]);

        $preference->update($validatedData);
        return response()->json(['message' => 'User preference updated successfully', 'data' => $preference]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $preference = UserPreference::where('user_id', $user->id)->findOrFail($id);
        $preference->delete();

        return response()->json(['message' => 'User preference deleted successfully']);
    }
}
