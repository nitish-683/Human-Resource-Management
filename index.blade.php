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
                <table id="userTable" class="table">
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
                </table>
            </div>
        </div>
    </div>

    <script>
        $(function () {
            $('#userTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.users.index') }}',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'roles', name: 'roles', orderable: false, searchable: false },
                    { data: 'status', name: 'status', orderable: false, searchable: false },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                    { data: 'send_questions', name: 'send_questions', orderable: false, searchable: false },
                    { data: 'review_status', name: 'review_status', orderable: false, searchable: false },
                    { data: 'response_view', name: 'response_view', orderable: false, searchable: false }
                ]
            });
        });
    </script>
@endsection