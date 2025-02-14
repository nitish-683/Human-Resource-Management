@extends('layouts.master')
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="float-start">
                Candidate List
            </div>
            @can('permission_create')
                <div class="float-end">
                    <a class="btn btn-success btn-sm text-white" href="{{ route('admin.candidates.create') }}">
                        Add Candidate
                    </a>
                </div>
            @endcan
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Sr. No.</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Documents Verified</th>
                        <th>Download Documents</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($candidates as $key => $candidate)
                        <tr data-entry-id="{{ $candidate->id }}">
                        <td>
                            {{ $key + 1 + ($candidates->currentPage() - 1) * $candidates->perPage() }}
                            </td>
                            <td>{{ $candidate->name ?? '' }}</td>
                            <td>{{ $candidate->email ?? '' }}</td>
                            <td>{{ $candidate->phone ?? '' }}</td>
                            <td>
                                @if($candidate->documents_verified == 1)
                                    Verified
                                    @else
                                    @if(count($candidate->documents))
                                    <button class="btn btn-link text-primary" onclick="verifyDocument({{ $candidate->id }})" style="border: none; background: none; padding: 0; display: flex; align-items: center;">
                                        <i class="fa fa-check-circle-o" style="margin-right: 5px;"></i> Verify
                                    </button>
                                    @else
                                    {{'-'}}
                                    @endif
                                @endif

                            </td>
                            <td>
                                @if(count($candidate->documents))
                                 <a href="javascript:void(0);" class="download-docs-btn" title="Download all documents" data-candidate-id="{{ $candidate->id }}"><i class="fa fa-file-pdf-o text-danger icon-large"></i></a> 
                               @else 
                                 {{ '-'}}
                               @endif
                            </td>
                            <td>
                                @can('permission_edit')
                                    <a class="badge bg-info" href="{{ route('admin.candidates.edit', $candidate->id) }}">
                                        Edit
                                    </a>
                                @endcan

                                @can('permission_delete')
                                    <form id="delete-form-{{ $candidate->id }}" method="post"
                                          action="{{ route('admin.candidates.destroy', $candidate->id) }}" style="display: none">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                    </form>
                                    <a href="javascript:void(0)" class="badge bg-danger text-white" onclick="if(confirm('Are you sure, You want to Delete this ??')) { event.preventDefault(); document.getElementById('delete-form-{{ $candidate->id }}').submit(); }">Delete</a>
                                @endcan

                                @can('permission_makeEmployee')
                                @if($candidate->canConvertToEmployee())
                                    <a class="badge bg-success text-white" href="{{ route('admin.candidates.convert', $candidate->id) }}">
                                        Convert to Employee
                                    </a>
                                @endif
                            @endcan

                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer clearfix">
            {{ $candidates->links() }}
        </div>
    </div>

    <script>
       function verifyDocument(candidateId) {
            if (confirm('Are you sure you want to verify this candidate\'s documents?')) {
                // Send an AJAX request to update the 'documents_verified' field
                fetch(`/admin/candidates/${candidateId}/verify`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        documents_verified: 1
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        alert('Documents verified successfully!');
                        location.reload(); // Reload the page to reflect the changes
                    } else {
                        alert(data.message || 'Failed to verify documents.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while verifying the documents. Please check the console for more details.');
                });
            }
        }

        $(document).on('click', '.download-docs-btn', function() {
            var candidateId = $(this).data('candidate-id');
            $.ajax({
                url: '/admin/candidate/documents/download', 
                method: 'POST',
                data: {
                    candidate_id: candidateId,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.message === 'Documents ready for download.' && response.zipFileUrl) {
                        // Create a link to download the ZIP file
                        var a = document.createElement('a');
                        a.href = response.zipFileUrl;
                        a.setAttribute('download', response.zipFileUrl.split('/').pop()); 
                        a.style.display = 'none';
                        document.body.appendChild(a);

                        // Trigger the download
                        a.click();
                        document.body.removeChild(a);
                    } else {
                        alert('No documents available for download.');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error: ' + xhr.responseText);
                }
            });
        });
    </script>
@endsection
