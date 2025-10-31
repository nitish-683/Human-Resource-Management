@extends('layouts.master')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="float-start">
                Document List
            </div>
            @can('document_create')
                <div class="float-end">
                    <a class="btn btn-success btn-sm text-white" href="{{ route("admin.documenttypes.create") }}">
                        Add Document Type
                    </a>
                </div>
            @endcan
        </div>
        <div class="card-body">
            <div class="table-responsive">
                 <table id="docTable" class="table Ytable">
                    <thead>
                    <tr>
                        <th>
                        Sr. No.
                        </th>
                        <th>
                            Name
                        </th>
                        <th>
                            Required
                        </th>
                        <th>
                            Format
                        </th> 
                        <th>
                            Added on
                        </th>
                        <th>
                            Action
                        </th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@parent
<script>
$(function () {
    $('#docTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.documenttypes.index') }}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'required', name: 'required' },
            { data: 'document_format', name: 'document_format' }, 
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
});
</script>
@endsection
