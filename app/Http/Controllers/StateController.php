<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\State;

class StateController extends Controller
{
    public function states(Request $request)
    {
   
        if (!hasPermission('States', 'view')) {
            abort(403, 'Unauthorized');
        }

        if ($request->ajax()) {
            $data = State::all();
            // DD($data); // no relation since no foreign key country setup
            return response()->json([
                'data' => $data,
                'canAdd' => hasPermission('States', 'add'),
                'canEdit' => hasPermission('States', 'edit'),
                'canDelete' => hasPermission('States', 'delete')
            ]);
        }

        return view('master.states');
    }

    public function storeState(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:states,name',
            'country_id' => 'required|numeric'
        ]);

        $state = State::create([
            'country_id' => $request->country_id,
            'name' => $request->name,
        ]);

        return response()->json([
            'data' => $state,
            'status' => 'success',
            'message' => 'State created successfully',
        ]);
    }

    public function editState($id)
    {
        $data = State::where('state_id', $id)->firstOrFail();
        return response()->json(['data' => $data]);
    }

    public function updateState(Request $request)
    {
        $request->validate([
            'state_id' => 'required|exists:states,state_id',
            'name' => 'required|string|unique:states,name,' . $request->state_id . ',state_id',
            'country_id' => 'required|numeric'
        ]);

        $state = State::where('state_id', $request->state_id)->firstOrFail();
        $state->update([
            'name' => $request->name,
            'country_id' => $request->country_id
        ]);

        return response()->json([
            'data' => $state,
            'status' => 'success',
            'message' => 'State updated successfully',
        ]);
    }

    public function deleteState(Request $request)
    {
        $state = State::where('state_id', $request->id)->firstOrFail();
        $state->delete();

        return response()->json([
            'data' => $state,
            'status' => 'success',
            'message' => 'State deleted successfully'
        ]);
    }
}
