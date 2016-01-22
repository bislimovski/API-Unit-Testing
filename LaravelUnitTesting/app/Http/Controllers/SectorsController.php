<?php

namespace App\Http\Controllers;

use App\Sector;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SectorsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Sector::paginate(), 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->only(['name', 'year', 'revenue']);

        try{
            Sector::create($input);
            return response()->json('Record is created', 201);
        } catch(\Exception $e)
        {
            return response()->json('Unprocessable Entity', 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sector = Sector::find($id);

        if($sector)
        {
            return response()->json($sector, 200);
        }
        return response()->json('Not Found', 404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $sector = Sector::find($id);
        $input = $request->except(['id', 'created_at', 'updated_at']);

        if($sector)
        {
            try{
                $this->validate($request, [
                    'name' => 'required|min:2',
                    'year' => 'required|min:4',
                    'revenue' => 'string|min:2'
                ]);

                $sector->update($input);
                return response()->json('Updated', 202);
            } catch(\Exception $e)
            {
                return response()->json('Unprocessable Entity', 422);
            }
        }
        return response()->json('Not Found', 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sector = Sector::find($id);

        if($sector)
        {
            $sector->delete();
            return response()->json('Deleted', 204);
        }
        return response()->json('Not Found', 404);
    }
}
