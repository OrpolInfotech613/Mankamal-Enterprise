@php
    $currentIndex = (($roles->currentPage() - 1) * $roles->perPage()) + 1;
@endphp
@foreach ($roles as $role)
    <tr>
        <td>{{ $currentIndex++ }}</td>
        <td>{{ $role->role_name }}</td>
        <td>
            <!-- Add buttons for actions like 'View', 'Edit' etc. -->
            <!-- <button class="btn btn-primary">Message</button> -->
            <div class="flex gap-2 justify-content-left">
                <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-primary mr-1 mb-2"> <i class="fas fa-edit"></i>
                </a>
                <form action="{{ route('roles.destroy', $role->id) }}" method="POST"
                    onsubmit="return confirm('Are you sure you want to delete this role?');"
                    style="display: inline-block;">
                    @csrf
                    @method('DELETE') <!-- Add this line -->
                    <button type="submit" class="btn btn-danger mr-1 mb-2"><i class="fas fa-trash-alt"></i></button>
                </form>
            </div>
        </td>
    </tr>
@endforeach
