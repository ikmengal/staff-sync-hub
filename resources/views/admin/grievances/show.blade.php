<table class="table table-bordered table-striped">
    <tr>
        <th>Company</th>
        <td>
            {{ $company ?? '' }}
        </td>
    </tr>
    <tr>
        <th>User</th>
        <td>
            {{ getUserName2($gravience->hasUser) }}
        </td>
    </tr>
    <tr>
        <th>Creator</th>
        <td>
            {{ getUserName2($gravience->hasCreator) }}
        </td>
    </tr>
    <tr>
        <th>Date</th>
        <td>
            {{ $gravience->created_at->format("M ,d Y h:i A") }}
        </td>
    </tr>
    <tr>
        <th>Anonymous</th>
        <td>
            {{ $gravience->anonymous== 1 ? 'Yes' : 'No' }}
        </td>
    </tr>
    <tr>
        <th>Status</th>
        <td>
            {{ $gravience->status== 1 ? 'Active' : 'De-Active' }}
        </td>
    </tr>
    <tr>
        <th>Description</th>
        <td>
            {!! $gravience->description ?? '' !!}
        </td>
    </tr>
</table>