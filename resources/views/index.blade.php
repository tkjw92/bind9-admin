@extends('layouts.main')

@section('content')
    <div class="card">
        <div class="card-header row align-items-center">
            <h5 class="col">Zones</h5>
            <button class="btn btn-primary right" data-toggle="modal" data-target="#staticBackdrop">Add <i class="fas fa-plus pl-2"></i></button>
        </div>

        <div class="card-body">
            <div class="row row-cols-4 justify-content-center">
                @foreach ($zones as $zone)
                    <div class="card col m-2 p-3 {{ $zone->type == 'master' ? 'bg-primary' : 'bg-secondary' }}">
                        <a href="/zone/{{ $zone->name }}">{{ $zone->name }}</a>
                    </div>
                @endforeach
                @foreach ($ptrs as $ptr)
                    <div class="card col m-2 p-3 {{ $ptr->type == 'master' ? 'bg-warning' : 'bg-secondary' }}">
                        <a href="/ptr/{{ $ptr->ptr }}">{{ $ptr->ptr }}</a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Add Zones</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="/zone/add" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="type">Type</label>
                            <select name="type" id="type" class="form-control" required>
                                <option value="master">Master</option>
                                <option value="slave">Slave</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>

                        <div class="form-group">
                            <label for="peers">Neighbor Master / Slave</label>
                            <p class="text-red text-sm">(*) Optional when type is master</p>
                            <input type="text" class="form-control" id="peers" name="peers" placeholder="ex: 192.168.123.1;">
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
