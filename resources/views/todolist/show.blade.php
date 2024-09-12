@extends('layout')
@section('content')
    <section class="todoList mt-5 border rounded p-5 bg-light">
        <div class="loader loader_9" style="display: none">
            <div class="lds-ripple">
                <div></div>
                <div></div>
            </div>
        </div>
        <div class="todoHeading">
            <div class="heading border-bottom py-3 mb-3">
                <h2 class="text-info">PHP - Simple To Do List App</h2>
            </div>
            <div class="form py-3">
                <h3 class="text-success text-center py-3">
                    @if (session('message'))
                        {{ session('message') }}
                    @endif
                </h3>
                <div class="d-flex justify-content-center align-item-center">
                    <input type="text" name="task" id="task" class="form-control w-25 d-block mx-2" />
                    <button type="button" class="btn btn-primary addTask">Add Task</button>
                </div>
                <p class="error text-center text-danger pt-2"></p>
            </div>
        </div>
        <div class="todoForm py-5">
            @if ($todo->total() == 0)
                <h3 class="text-center text-secondary">
                    No Task Found
                </h3>
            @else
                @php
                    $count = ($todo->currentPage() - 1) * $todo->perPage() + 1;
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
                            <td class="d-flex">
                                @if ($item->status == 0)
                                    <button type="submit" class="btn btn-success done mx-1">
                                        <i class="far fa-check-square"></i>
                                    </button> |
                                @endif

                                <button type="submit" class="btn btn-danger delete mx-1">
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
        </div>

    </section>
@endsection

@section('scripts')
    <script>
        jQuery(document).ready(() => {
            jQuery(".form-control").click(() => {
                jQuery(".error").text("");
            })
            jQuery(".addTask").click(function() {
                let task = $("#task").val().trim();
                flag = true;
                console.log(task.length);
                if (task.length == 0) {
                    jQuery(".error").text("Please Enter Task");
                    flag = false;
                } else {
                    if (!task.match(/^(?!\d+$)[a-zA-Z0-9 ]+$/)) {
                        jQuery(".error").text("Only Alphabets and Alphanumeric are allowed");
                        flag = false;
                    }
                }
                if (flag) {


                    jQuery.ajax({
                        type: "post",
                        url: 'todolist',
                        headers: {
                            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            task: task
                        },
                        beforeSend: function() {
                            jQuery(".loader").show();
                        },
                        success: function(response) {
                            jQuery(".loader").hide();
                            jQuery(".text-success").text("Task Added Successfully");
                            jQuery(".todoForm").html(response);
                            setTimeout(() => {
                                jQuery(".text-success").text("");
                            }, 2000);
                        },

                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                            let result = XMLHttpRequest.responseJSON;
                            if (result.error == "23000") {
                                jQuery(".loader").hide();
                                jQuery(".error").text(task + " is already exist");
                            }
                        }
                    })
                }
            });
            jQuery(document).on('click', '.delete', function() {
                let confirmation  = confirm("Are you sure want to delete this task");
                let task = $(this).parent().parent().find("td").eq(1).text();
                flag = true;

               if(confirmation)
               {
                jQuery.ajax({
                    type: "DELETE",
                    url: `todolist/${task}`,
                    headers: {
                        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                    },

                    beforeSend: function() {
                        jQuery(".loader").show();
                    },
                    success: function(response) {
                        jQuery(".loader").hide();
                        jQuery(".text-success").text("Task has been Deleted");
                        jQuery("button").attr("disabled", true);
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        let result = XMLHttpRequest.responseJSON;
                        console.log(result);
                    }
                })
               }
            });
            jQuery(document).on('click', '.done', function() {
                let task = $(this).parent().parent().find("td").eq(1).text();
                flag = true;

                jQuery.ajax({
                    type: "PUT",
                    url: `todolist/${task}`,
                    headers: {
                        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        jQuery(".loader").show();
                    },
                    success: function(response) {
                        jQuery(".loader").hide();
                        jQuery(".text-success").text("Task Updated");
                        jQuery("button").attr("disabled", true);
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        let result = XMLHttpRequest.responseJSON;
                        console.log(result);
                    }
                })
            });
        })
    </script>
@endsection
