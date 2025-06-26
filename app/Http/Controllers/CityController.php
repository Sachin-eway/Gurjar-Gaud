<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\State;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CityController extends Controller
{
    // Load the City master view
    public function city()
    {
        return view('master.city');
    }

    // Fetch all cities for DataTable
    public function cities()
    {
        $cities = City::with(['state', 'district'])->get()->map(function ($city, $index) {
            return [
                'id' => $city->id,
                'city' => $city->city,
                'district_name' => $city->district->name ?? '',
                'state_name' => $city->state->name ?? '',
                'status' => $city->status,
                'sequence_number' => $index + 1,
                'canEdit' => true, // adjust as per permissions
                'canDelete' => true, // adjust as per permissions
            ];
        });

        // Optional: Uncomment to debug output in browser (for testing only)
        // dd($cities);

        return response()->json([
            'data' => $cities,
            'canAdd' => true,
            'canEdit' => true,
            'canDelete' => true,
        ]);
    }

    // Store a new city
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'city' => 'required|string|max:255',
            'state_id' => 'required|exists:states,state_id',
            'district_id' => 'required|exists:districts,distt_id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $city = City::create([
            'city' => $request->city,
            'state_id' => $request->state_id,
            'district_id' => $request->district_id,
        ]);

        return response()->json(['message' => 'City created successfully', 'data' => $city]);
    }

    // Fetch a single city for editing
    public function edit($id)
    {
        $city = City::with(['state', 'district'])->findOrFail($id);
        return response()->json($city);
    }

    // Update an existing city
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:city,id',
            'city' => 'required|string|max:255',
            'state_id' => 'required|exists:states,state_id',
            'district_id' => 'required|exists:districts,distt_id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $city = City::findOrFail($request->id);
        $city->update([
            'city' => $request->city,
            'state_id' => $request->state_id,
            'district_id' => $request->district_id,
        ]);

        return response()->json(['message' => 'City updated successfully']);
    }

    // Toggle city status
    public function changeStatus(Request $request)
    {
        $city = City::findOrFail($request->id);
        $city->status = $city->status ? 0 : 1;
        $city->save();

        return response()->json(['message' => 'City status updated']);
    }

    // Soft delete city
    public function delete(Request $request)
    {
        $city = City::findOrFail($request->id);
        $city->delete();

        return response()->json(['message' => 'City deleted successfully']);
    }
}
