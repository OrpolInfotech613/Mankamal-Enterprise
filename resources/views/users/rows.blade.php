@php
    $currentIndex = (($users->currentPage() - 1) * $users->perPage()) + 1;
@endphp
@foreach ($users as $user)
    <tr>
        <td>{{ $currentIndex++ }}</td>
        <td>{{ $user->name }}</td>
        <td>{{ $user->email }}</td>
        <td>{{ $user->role_data->role_name ?? '-' }}</td>
        <td>
            <!-- Add buttons for actions like 'View', 'Edit' etc. -->
            <!-- <button class="btn btn-primary">Message</button> -->
            <div class="flex gap-2 justify-content-left">
                <a href="{{ route('users.show', $user->id) }}"
                    class="btn btn-primary mr-1 mb-2"><i class="fas fa-eye"></i>
                </a>
                <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                    onsubmit="return confirm('Are you sure you want to delete this user?');"
                    style="display: inline-block;">
                    @csrf
                    @method('DELETE') <!-- Add this line -->
                    <button type="submit" class="btn btn-danger mr-1 mb-2"><i class="fas fa-trash-alt"></i></button>
                </form>
                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning mr-1 mb-2">
                    <i class="fas fa-edit text-white"></i>
                </a>
            </div>
        </td>
    </tr>
@endforeach