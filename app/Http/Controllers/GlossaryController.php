<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Glossary;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;

class GlossaryController extends Controller
{
    public function getGlossary()
    {
        $terms = Glossary::get();

        return inertia('glossary/glossary',[
            'terms' => $terms
        ]); 

    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'term' => 'required|string',
            'description' => 'required|string'
        ]);

        Glossary::create($validated);

        return back();
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'term' => 'required|string',
            'description' => 'required|string'
        ]);

        Glossary::findOrFail($id)->update($validated);

        return back();
    }

    public function delete(string $id)
    {
        Glossary::findOrFail($id)->delete();

        return back();
    }


}
