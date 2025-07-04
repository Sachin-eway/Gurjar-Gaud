<?php

namespace App\Http\Controllers;

use App\Models\CensusMember;
use App\Models\CensusForm;
use Illuminate\Http\Request;

class CensusMemberController extends Controller
{
    // List members (JSON for DataTables or Blade view)
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $members = CensusMember::with('form')->latest()->get();
            return response()->json([
                'data' => $members,
                'canAdd' => hasPermission('Census Members', 'add'),
                'canEdit' => hasPermission('Census Members', 'edit'),
                'canDelete' => hasPermission('Census Members', 'delete'),
            ]);
        }

        return view('census.members'); // Blade view
    }

    // Store a new member
    public function store(Request $request)
    {
        $validated = $request->validate([
            'census_form_id' => 'required|exists:census_forms,id',
            'full_name' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female,Other',
            'dob' => 'nullable|date',
            'marital_status' => 'nullable|in:Unmarried,Married,Widowed,Divorced,Separated',
            'education' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'income_source' => 'nullable|string|max:255',
            'mobile' => 'nullable|string|max:255',
            'whatsapp' => 'nullable|string|max:255',
            'identity_proof' => 'nullable|string|max:255',
        ]);

        $member = CensusMember::create($validated);

        return response()->json([
            'data' => $member,
            'message' => 'Member added successfully',
            'status' => 'success',
        ]);
    }

    // Get a member for editing
    public function edit($id)
    {
        $member = CensusMember::findOrFail($id);
        return response()->json(['data' => $member]);
    }

    // Update member
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:census_members,id',
            'census_form_id' => 'required|exists:census_forms,id',
            'full_name' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female,Other',
            'dob' => 'nullable|date',
            'marital_status' => 'nullable|in:Unmarried,Married,Widowed,Divorced,Separated',
            'education' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'income_source' => 'nullable|string|max:255',
            'mobile' => 'nullable|string|max:255',
            'whatsapp' => 'nullable|string|max:255',
            'identity_proof' => 'nullable|string|max:255',
        ]);

        $member = CensusMember::findOrFail($request->id);
        $member->update($request->except('id'));

        return response()->json([
            'data' => $member,
            'message' => 'Member updated successfully',
            'status' => 'success',
        ]);
    }

    // Delete member
    public function destroy(Request $request)
    {
        $member = CensusMember::findOrFail($request->id);
        $member->delete();

        return response()->json([
            'message' => 'Member deleted successfully',
            'status' => 'success',
        ]);
    }
}
