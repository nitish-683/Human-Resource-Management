<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DocumentType;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class DocumentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort_if(Gate::denies('document_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $documentTypes = DocumentType::latest()->paginate(15);
        return view('admin.document_types.index', compact('documentTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if(Gate::denies('document_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.document_types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        DocumentType::create($request->all());
        toast('Document Type created successfully.','success');
        return redirect()->route('admin.documenttypes.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(DocumentType $documentTypes)
    {
        return view('admin.document_types.show', compact('documentType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DocumentType $documenttype)
    {
        abort_if(Gate::denies('document_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.document_types.edit', compact('documenttype'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DocumentType $documenttype)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $documenttype->update($request->all());
        toast('Document Type updated successfully.','success');
        return redirect()->route('admin.documenttypes.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DocumentType $documentType)
    {
        abort_if(Gate::denies('document_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $documentType->delete();
        toast('Document Type deleted successfully.','success');
        return redirect()->route('admin.document_types.index');
    }
}
