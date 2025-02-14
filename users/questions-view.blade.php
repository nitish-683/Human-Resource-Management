@extends('layouts.master')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="float-start">
               User Response
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>
                        Questions
                        </th>
                        <td>
                            Answers
                        </td>
                        </tr>
                    <tr>
                        <th>
                        Handbook Received
                        </th>
                        <td>
                            {{$policy->handbook_received}}
                        </td>
                        </tr>

                        <tr>
                        <th>
                        Handbook Purpose
                        </th>
                        <td>
                        {{$policy->handbook_purpose}}
                        </td>
                        </tr>

                        <tr>
                        <th>
                        Policy Clarity
                        </th>
                        <td>
                        {{$policy->policy_clarity}}
                        </td>
                        </tr>

                        <tr>
                        <th>
                        Harassment Policy
                        </th>
                        <td>
                        {{$policy->harassment_policy}}
                        </td>
                        </tr>

                        <tr>
                        <th>
                        Violation Steps
                        </th>
                        <td>
                        {{$policy->violation_steps}}
                        </td>
                        </tr>

                        <tr>
                        <th>
                        Leave Policy
                        </th>
                        <td>
                        {{$policy->leave_policy}}
                        </td>
                        </tr>

                        <tr>
                        <th>
                        Formal Day
                        </th>
                        <td>
                        {{$policy->formal_day}}
                        </td>
                        </tr>

                        <tr>
                        <th>
                        Casual Leaves
                        </th>
                        <td>
                        {{$policy->casual_leaves}}
                        </td>
                        </tr>
                        <tr>
                        <th>
                        Policies Fair
                        </th>
                        <td>
                        {{$policy->policies_fair}}
                        </td>
                        </tr>
                        <tr>
                        <th>
                        Policy Update
                        </th>
                        <td>
                        {{$policy->policy_update}}
                        </td>
                        </tr>
                        <tr>
                        <th>
                        Handbook Help
                        </th>
                        <td>
                        {{$policy->handbook_help}}
                        </td>
                        </tr>
                        <tr>
                        <th>
                        Handbook Help Details
                        </th>
                        <td>
                        {{$policy->handbook_help_details}}
                        </td>
                        </tr>
                        <tr>
                        <th>
                        Accessibility Suggestions
                        </th>
                        <td>
                        {{$policy->accessibility_suggestions}}
                        </td>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection
