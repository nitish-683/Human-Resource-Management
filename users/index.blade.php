@extends('layouts.master')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="float-start">
                User List
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>
                            Sr. No.
                        </th>
                        <th>
                            Name
                        </th>
                        <th>
                            Email
                        </th>
                        <th>
                            Roles
                        </th>
                        <th>
                            Status
                        </th>
                        <th>
                            Register At
                        </th>
                        <th>
                            Action
                        </th>
                        <th>
                            Send/Resend Questions
                        </th>
                        <th>
                            Review status
                        </th>
                        <th>
                           View Questions Response
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($users as $index => $user)
                        <tr data-entry-id="{{ $user->id }}">
                        <td>
                            {{ $index + 1 + ($users->currentPage() - 1) * $users->perPage() }}
                        </td>
                            <td>
                                {{ $user->name ?? '' }}
                            </td>
                            <td>
                                {{ $user->email ?? '' }}
                            </td>
                            <td>
                                @foreach($user->getRoleNames() as $item)
                                    <span class="badge bg-info">{{ $item }}</span>
                                @endforeach
                            </td>
                            <td>
                                @if($user->status)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Blocked</span>
                                @endif
                            </td>
                            <td>
                                {{ $user->created_at->format('Y-m-d') ?? '' }}
                            </td>
                            <td>
                                @can('user_edit')
                                    <a class="badge bg-info" href="{{ route('admin.users.edit', $user->id) }}">
                                        Edit
                                    </a>
                                @endcan
                                @if (auth()->user()->hasRole('Admin'))
                                    @if($user->status)
                                        <a href="{{ route('admin.user.banUnban', ['id' => $user->id, 'status' => 0]) }}" class="badge bg-danger">Block</a>
                                    @else
                                        <a href="{{ route('admin.user.banUnban', ['id' => $user->id, 'status' => 1]) }}" class="badge bg-info">Unblock</a>
                                    @endif
                                @endif
                            </td>
                            <td>
                                @if ($user->review_status != 1 && auth()->user()->hasRole('Admin') && !empty($user->candidate_id))
                                    <a href="{{ route('admin.user.send.questions', ['id' => $user->id]) }}" class="badge bg-info">{{ ($user->checkResponseData())?'Resend':'Send'}} Questions</a>
                                @else
                                    {{'-'}}
                                @endif
                            </td>
                            <td>
                                @if (auth()->user()->hasRole('Admin') && !empty($user->candidate_id))
                                    @if($user->review_status)
                                        <a href="{{ route('admin.user.review.status', ['id' => $user->id, 'status' => 0]) }}" title="Change to Pending" class="badge bg-success">Pass</a>
                                    @else
                                        <a href="{{ route('admin.user.review.status', ['id' => $user->id, 'status' => 1]) }}" title="Change to Pass" class="badge bg-danger">Pending</a>
                                    @endif
                                @else
                                    {{'-'}}
                                @endif
                            </td>

                            <td>
                                @if (auth()->user()->hasRole('Admin') && !empty($user->candidate_id))
                                    <a href="{{ route('admin.user.questions.response',  $user->id) }}" class="badge bg-warning">View</a>                                   
                                @else
                                    {{'-'}}
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                <p>No Employee Found</p>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer clearfix">
            {{ $users->links() }}
        </div>
    </div>
@endsection
