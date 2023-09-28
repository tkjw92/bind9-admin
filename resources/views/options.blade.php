@extends('layouts.main')

@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h5>Options</h5>
        </div>

        <div class="card-body">
            <form action="" method="post">
                @csrf

                <div class="form-group">
                    <h3>Allowed</h3>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" {{ $conf->allowed == 'any;' ? 'checked' : '' }} id="any">
                        <label class="form-check-label" for="any">
                            Allow For Any
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" {{ $conf->allowed == 'custom' ? 'checked' : '' }} id="custom">
                        <label class="form-check-label" for="custom">
                            Custom
                        </label>
                    </div>

                    <input type="text" class="form-control w-25" id="input" {{ $conf->allowed == 'any;' ? 'disabled' : '' }} required value="{{ $conf->allowed }}" name="allowed">
                    <p class="text-danger text-sm">(*) Don't forget to always add ; to the end</p>
                </div>

                <div class="form-group">
                    <h3>Recursion</h3>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="recursion" {{ $conf->recursion == 'yes' ? 'checked' : '' }} id="recursion" name="recursion">
                        <label class="form-check-label" for="recursion">
                            Enable recursion
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <h3>Forwarders</h3>

                    <div class="form-group">
                        <input type="text" class="form-control w-25 mb-1" name="f1" placeholder="ex: 8.8.8.8" value="{{ $conf->f1 }}">
                        <input type="text" class="form-control w-25 mb-1" name="f2" placeholder="ex: 8.8.4.4" value="{{ $conf->f2 }}">
                        <input type="text" class="form-control w-25 mb-1" name="f3" placeholder="ex: 1.1.1.1" value="{{ $conf->f3 }}">
                        <input type="text" class="form-control w-25 mb-1" name="f4" placeholder="ex: 1.0.0.1" value="{{ $conf->f4 }}">
                    </div>

                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>

            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        const any = document.getElementById('any');
        const custom = document.getElementById('custom');
        const input = document.getElementById('input');

        any.addEventListener('change', () => {
            if (any.checked) {
                custom.checked = false;
                input.disabled = true;
                input.value = 'any;';
            } else {
                custom.checked = true;
            }
        })

        custom.addEventListener('change', () => {
            if (custom.checked) {
                any.checked = false;
                input.disabled = false;
                input.value = '';
            }
        })
    </script>
@endsection
