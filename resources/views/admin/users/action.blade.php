

<div class="dropdown">
    <a class="btn" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fa fa-ellipsis-vertical" style="font-size: 26px;"></i>
    </a>

    <ul class="dropdown-menu">

        <li>
        @can('users-direct-permission')
         <a href="#" class="dropdown-item addPermission"
                    data-route="{{ route('users.directPermission', $user->id) }}" data-id="{{$user->id}}">
                 Permissions
                </a>
        @endcan
        </li>
        <li>
            @can('users-delete')
                <a href="#"
                    class="dropdown-item" onclick="deleteUser($(this))" data-del-url="{{ route('users.destroy',$user->id) }}" data-id={{$user->id}}>
                   Delete
                </a>
            @endcan
        </li>
        <li>
            @can('users-edit')
            <a href="#" class="btn edit-btn btn-active-light-primary "   data-type="edit" data-edit-url="{{ route('users.edit',$user->id) }}">
             Edit
            </a>
            @endcan
        </li>
     



    </ul>
</div>
