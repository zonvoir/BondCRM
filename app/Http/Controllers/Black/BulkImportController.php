<?php

namespace App\Http\Controllers\Black;

use App\Http\Controllers\Controller;
use App\Imports\LeadsImport;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class BulkImportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Black/Index', ['user' => User::orderByDesc('id')->paginate(10)]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $path = $request->file('file');
        $data = Excel::toArray([], $path);

        try {
            // Perform the import
            Excel::import(new LeadsImport, $request->file);

            // Flash a success message to the session
            return redirect()->route('email.index')->with('success', 'Data imported successfully!');
        } catch (Exception $e) {
            dd($e->getMessage());

            // Flash an error message to the session
            return redirect()->route('email.index')->with('error', 'There was an issue with the import: '.$e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
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
}
