<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\District;

class DistrictController extends Controller
{
    public function districts(Request $request)
    {
        if (!hasPermission('Districts', 'view')) abort(403);

        if ($request->ajax()) {
            $data = District::with('state')->get()->map(function ($d) {
                $d->state_name = $d->state->name ?? '';
                return $d;
            });

            return response()->json([
                'data' => $data,
                'canAdd' => hasPermission('Districts', 'add'),
                'canEdit' => hasPermission('Districts', 'edit'),
                'canDelete' => hasPermission('Districts', 'delete'),
            ]);
        }

        return view('master.districts');
    }

    public function storeDistrict(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|unique:districts,name,NULL,distt_id,state_id,' . $request->state_id,
            'state_id' => 'required|exists:states,state_id',
        ]);

        $district = District::create([
            'state_id' => $request->state_id,
            'name'     => $request->name,
        ]);

        return response()->json([
            'data' => $district,
            'status' => 'success',
            'message' => 'District added successfully',
        ]);
    }

    public function editDistrict($id)
    {
        $district = District::findOrFail($id);
        return response()->json(['data' => $district]);
    }

    public function updateDistrict(Request $request)
    {
        $request->validate([
            'distt_id' => 'required|exists:districts,distt_id',
            'name'     => 'required|string|unique:districts,name,' . $request->distt_id . ',distt_id,state_id,' . $request->state_id,
            'state_id' => 'required|exists:states,state_id',
        ]);

        $district = District::findOrFail($request->distt_id);
        $district->update([
            'name'     => $request->name,
            'state_id' => $request->state_id,
        ]);

        return response()->json([
            'data' => $district,
            'status' => 'success',
            'message' => 'District updated successfully',
        ]);
    }

    public function deleteDistrict(Request $request)
    { 
        $district = District::where('distt_id', $request->id)->firstOrFail();
        $district->delete();
        return response()->json([
            'data' => $district,
            'status' => 'success',
            'message' => 'District deleted successfully',
        ]);
    }

    
    public function changeDistrictStatus(Request $request)
    {
        abort(404); 
    }
}
