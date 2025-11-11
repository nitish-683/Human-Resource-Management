@extends('layouts.master')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="float-start">
                Domain List
            </div>
            @can('domain_create')
                <div class="float-end">
                    <a class="btn btn-success btn-sm text-white" href="{{ route('admin.domain.create') }}">
                        Add Domain
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
                        <th>Domain Name</th>
                        <th>Status</th>
                        <th>Registered At</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($domains as $index => $domain)
                        <tr data-entry-id="{{ $domain->id }}">
                            <td>{{ $index + 1 + ($domains->currentPage() - 1) * $domains->perPage() }}</td>
                            <td>{{ $domain->name ?? '' }}</td>
                            <td>
                                @if($domain->status)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td>{{ $domain->created_at->format('d-m-Y') ?? '' }}</td>
                            <td>
                                @can('domain_edit')
                                    <a class="badge bg-info" href="{{ route('admin.domain.edit', $domain) }}">
                                        Edit
                                    </a>
                                @endcan
                                @can('domain_delete')
                                    <form id="delete-form-{{ $domain->id }}" method="post"
                                          action="{{ route('admin.domain.delete', $domain) }}"
                                          style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    <a href="javascript:void(0)" class="badge bg-danger text-white" onclick="
                                        if(confirm('Are you sure you want to delete this domain?'))
                                        {
                                            event.preventDefault();
                                            document.getElementById('delete-form-{{ $domain->id }}').submit();
                                        }">
                                        Delete
                                    </a>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">
                                No Domains Found
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer clearfix">
            {{ $domains->links() }}
        </div>
    </div>
@endsection
