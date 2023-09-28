@extends('layouts.main')

@section('content')
    <div class="card">
        <div class="card-header row align-items-center">
            <h5 class="col">{{ $name }}</h5>
            <div class="row col d-flex flex-column">
                <div class="col justify-content-end d-flex">
                    <button class="btn btn-primary btn-sm mx-2" data-toggle="modal" data-target="#addModal" {{ $zone->type == 'slave' ? 'disabled' : '' }}>Add New Record</button>
                    <button class="btn btn-primary btn-sm mx-2" data-toggle="modal" data-target="#ptrModal" {{ $ptr > 0 ? 'disabled' : '' }}>Add to PTR</button>
                    <button class="btn btn-danger btn-sm mx-2" data-toggle="modal" data-target="#deleteModal">Delete Zone</button>
                </div>
                <div class="col mt-3">
                    <form action="/update/peers" method="post">
                        <div class="input-group">
                            @csrf
                            <input type="hidden" name="zone" value="{{ $name }}">
                            <label class="mx-2">Neighbor Master / Slave</label>
                            <input type="text" name="peers" class="form-control form-control-sm" placeholder="ex: 192.168.123.1;" value="{{ $zone?->peers }}">
                            <button type="submit" class="btn btn-primary btn-sm ml-3">Save</button>
                        </div>
                    </form>
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
                            <td class="text-uppercase">{{ $record->type }}</td>
                            <td>{{ $record->name }}</td>
                            <td>{{ $record->content }}</td>
                            <td>
                                <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editModal{{ $record->id }}">Edit</button>
                                <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteRecord{{ $record->id }}">Delete</button>
                            </td>
                        </tr>

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
                                        <a href="/zone/record/delete/{{ $record->id }}" class="btn btn-danger">Delete</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal -->
                        <div class="modal fade" id="editModal{{ $record->id }}" tabindex="-1" aria-labelledby="editModal{{ $record->id }}Label" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModal{{ $record->id }}Label">Add New Records</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="/zone/record/edit/{{ $record->id }}" method="post">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Name</label>
                                                <input type="text" class="form-control" name="name" required value="{{ $record->name }}">
                                            </div>
                                            <div class="form-group">
                                                <label>Type</label>
                                                <select name="type" class="form-control" required>
                                                    <option value="">Select</option>
                                                    <option value="a" {{ $record->type == 'a' ? 'selected' : '' }}>A</option>
                                                    <option value="cname" {{ $record->type == 'cname' ? 'selected' : '' }}>CNAME</option>
                                                    <option value="mx" {{ $record->type == 'mx' ? 'selected' : '' }}>MX</option>
                                                    <option value="txt" {{ $record->type == 'txt' ? 'selected' : '' }}>TXT</option>
                                                </select>
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
                    @endforeach
                </tbody>
            </table>
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
                    <a href="/zone/{{ $name }}/delete" class="btn btn-danger">Delete</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="ptrModal" tabindex="-1" aria-labelledby="ptrModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ptrModalLabel">Confirm Box</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="/ptr/add" method="post">
                    @csrf
                    <input type="hidden" name="name" value="{{ $name }}">
                    <div class="modal-body">
                        <h5>Are you sure want add PTR record to this zone ?</h5>
                        <div class="form-group">
                            <label>Zone PTR Name</label>
                            <input type="text" class="form-control" name="ptr" required placeholder="ex: 123.168.192.in-addr.arpa">
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
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Add New Records</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="/zone/record/{{ $name }}/add" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" name="name" id="name" required>
                        </div>
                        <div class="form-group">
                            <label for="type">Type</label>
                            <select name="type" id="type" class="form-control" required>
                                <option value="">Select</option>
                                <option value="a">A</option>
                                <option value="cname">CNAME</option>
                                <option value="mx">MX</option>
                                <option value="txt">TXT</option>
                            </select>
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
@endsection
