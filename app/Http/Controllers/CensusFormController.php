<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CensusForm;

class CensusFormController extends Controller
{
    public function censusForms(Request $request)
    {
        if (!hasPermission('Census Forms', 'view')) abort(403);

        if ($request->ajax()) {
            $data = CensusForm::latest()->get()->map(function ($f, $i) {
                $f->sequence_number = $i + 1;
                return $f;
            });

            return response()->json([
                'data' => $data,
                'canAdd' => hasPermission('Census Forms', 'add'),
                'canEdit' => hasPermission('Census Forms', 'edit'),
                'canDelete' => hasPermission('Census Forms', 'delete'),
            ]);
        }

        return view('census.list');
    }

    public function storeForm(Request $request)
    {
        $request->validate([
            'head_name' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female,Other',
            'dob' => 'required|date',
            'contact_number' => 'required|digits_between:10,15',
            'family_uid' => 'nullable|unique:census_forms,family_uid',
            'father_or_husband_name' => 'nullable|string|max:255',
            'caste' => 'nullable|string|max:255',
            'family_deity' => 'nullable|string|max:255',
            'bank_account' => 'nullable|string|max:50',
            'whatsapp' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:255',
            'identity_proof' => 'nullable|string|max:255',
            'current_address' => 'nullable|string',
            'permanent_address' => 'nullable|string',
            'total_members' => 'nullable|integer',
        ]);

        $form = CensusForm::create($request->all());

        return response()->json([
            'data' => $form,
            'status' => 'success',
            'message' => 'Census form added successfully',
        ]);
    }

    public function editForm($id)
    {
        $form = CensusForm::findOrFail($id);
        return response()->json(['data' => $form]);
    }

    public function updateForm(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:census_forms,id',
            'head_name' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female,Other',
            'dob' => 'required|date',
            'contact_number' => 'required|digits_between:10,15',
            'father_or_husband_name' => 'nullable|string|max:255',
            'caste' => 'nullable|string|max:255',
            'family_deity' => 'nullable|string|max:255',
            'bank_account' => 'nullable|string|max:50',
            'whatsapp' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:255',
            'identity_proof' => 'nullable|string|max:255',
            'current_address' => 'nullable|string',
            'permanent_address' => 'nullable|string',
            'total_members' => 'nullable|integer',
        ]);

        $form = CensusForm::findOrFail($request->id);
        $form->update($request->except('id'));

        return response()->json([
            'data' => $form,
            'status' => 'success',
            'message' => 'Census form updated successfully',
        ]);
    }

    public function deleteForm(Request $request)
    {
        $form = CensusForm::where('id', $request->id)->firstOrFail();
        $form->delete();

        return response()->json([
            'data' => $form,
            'status' => 'success',
            'message' => 'Census form deleted successfully',
        ]);
    }
}
