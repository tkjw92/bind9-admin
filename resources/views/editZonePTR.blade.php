@extends('layouts.main')

@section('content')
    <div class="card">
        <div class="card-header row align-items-center">
            <h5 class="col">{{ $ptr->ptr }}</h5>
            <div class="row col d-flex flex-column">
                <div class="col justify-content-end d-flex">
                    <button class="btn btn-primary btn-sm mx-2" data-toggle="modal" data-target="#addModal" {{ $ptr->type == 'slave' ? 'disabled' : '' }}>Add New Record</button>
                    <button class="btn btn-danger btn-sm mx-2" data-toggle="modal" data-target="#deleteModal">Delete Zone</button>
                </div>
            </div>
        </div>

        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Name</th>
                        <th>Content</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($records as $record)
                        <tr>
                            <td class="text-uppercase">PTR</td>
                            <td>{{ $record->name }}</td>
                            <td>{{ $record->content }}</td>
                            <td>
                                <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editRecord{{ $record->id }}">Edit</button>
                                <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteRecord{{ $record->id }}">Delete</button>
                            </td>
                        </tr>

                        <!-- Modal -->
                        <div class="modal fade" id="editRecord{{ $record->id }}" tabindex="-1" aria-labelledby="editRecord{{ $record->id }}Label" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editRecord{{ $record->id }}Label">Add New Records</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="/ptr/record/edit" method="post">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $record->id }}">
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Name</label>
                                                <input type="text" class="form-control" name="name" required value="{{ $record->name }}">
                                            </div>
                                            <div class="form-group">
                                                <label>Type</label>
                                                <input type="text" class="form-control disabled" value="PTR" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label>Content</label>
                                                <input type="text" class="form-control" name="content" required value="{{ $record->content }}">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Modal -->
                        <div class="modal fade" id="deleteRecord{{ $record->id }}" tabindex="-1" aria-labelledby="deleteRecord{{ $record->id }}Label" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteRecord{{ $record->id }}Label">Confirm Box</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <h5>Are you sure want delete this record ?</h5>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <a href="/ptr/record/delete/{{ $record->id }}" class="btn btn-danger">Delete</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Add New Records</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="/ptr/record/add" method="post">
                    @csrf
                    <input type="hidden" name="ptr" value="{{ $ptr->ptr }}">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" name="name" id="name" required>
                        </div>
                        <div class="form-group">
                            <label>Type</label>
                            <input type="text" class="form-control disabled" value="PTR" readonly>
                        </div>
                        <div class="form-group">
                            <label for="content">Content</label>
                            <input type="text" class="form-control" name="content" id="content" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Box</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5>Are you sure want delete this zone ?</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <a href="/ptr/{{ $ptr->ptr }}/delete" class="btn btn-danger">Delete</a>
                </div>
            </div>
        </div>
    </div>
@endsection
