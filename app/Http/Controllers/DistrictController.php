<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\District;

class DistrictController extends Controller
{
    public function districts(Request $request)
    {
        if (!hasPermission('Districts', 'view')) {
            abort(403, 'Unauthorized');
        }
    
        if ($request->ajax()) {
            $data = District::with('state')->get();
    
            // Add state_name field to each district
            $data = $data->map(function ($district) {
                $district->state_name = $district->state->name ?? '';
                return $district;
            });
            return response()->json([
                'data' => $data->toArray(),
                'canAdd' => hasPermission('Districts', 'add'),
                'canEdit' => hasPermission('Districts', 'edit'),
                'canDelete' => hasPermission('Districts', 'delete')
            ]);
            
        }
    
        return view('master.districts');
    }
    

    public function storeDistrict(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:districts,name,NULL,district_id,state_id,' . $request->state_id,
            'state_id' => 'required|exists:states,state_id'
        ]);

        $district = District::create([
            'state_id' => $request->state_id,
            'name' => $request->name,
        ]);

        return response()->json([
            'data' => $district,
            'status' => 'success',
            'message' => 'District created successfully',
        ]);
    }

    public function changeDistrictStatus(Request $request)
{
    $district = District::where('district_id', $request->id)->firstOrFail();
    $district->status = $district->status ? 0 : 1;
    $district->save();

    return response()->json([
        'status' => 'success',
        'message' => 'District status updated successfully.'
    ]);
}


    public function editDistrict($id)
    {
        $district = District::where('district_id', $id)->firstOrFail();
        return response()->json(['data' => $district]);
    }

    public function updateDistrict(Request $request)
    {
        $request->validate([
            'district_id' => 'required|exists:districts,district_id',
            'name' => 'required|string|unique:districts,name,' . $request->district_id . ',district_id,state_id,' . $request->state_id,
            'state_id' => 'required|exists:states,state_id'
        ]);

        $district = District::where('district_id', $request->district_id)->firstOrFail();
        $district->update([
            'name' => $request->name,
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
        $district = District::where('district_id', $request->id)->firstOrFail();
        $district->delete();

        return response()->json([
            'data' => $district,
            'status' => 'success',
            'message' => 'District deleted successfully',
        ]);
    }
}
