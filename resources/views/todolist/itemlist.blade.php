@if ($todo->total() == 0)

    <h3 class="text-center text-secondary">
        No Task Found
    </h3>
@else
    @php
        $count = (($todo->currentPage() - 1) * $todo->perPage()) + 1;
    @endphp
    <table class="table border-bottom">
        <tr>
            <th>#</th>
            <th>Task</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        @foreach ($todo as $item)
            <tr>
                <td>{{ $count++ }}</td>
                <td>{{ $item->task }}</td>
                <td>{{ $item->status == 0 ? 'Pending' : 'Done' }}</td>
                <td>
                    @if ($item->status == 0)
                        <button type="button" class="btn btn-success done">
                            <i class="far fa-check-square"></i>
                        </button> |
                    @endif
                    <button type="button" class="btn btn-danger delete mx-1">
                        <i class="fas fa-times"></i>
                    </button>
                </td>
            </tr>
        @endforeach
    </table>
    <div class="paginate">
        {{ $todo->links() }}
    </div>
@endif
