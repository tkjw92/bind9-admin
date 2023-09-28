@extends('layouts.main')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5>Account Setting</h5>
        </div>
        <div class="card-body">
            <div class="login-box">
                <div class="card">
                    <div class="card-header">
                        <h5>Account</h5>
                    </div>
                    <div class="card-body">
                        <form action="" method="post" id="form">
                            @csrf
                            <input type="hidden" name="password" id="password">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Username" required name="username">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <input type="password" class="form-control" placeholder="Password" required id="password1">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <input type="password" class="form-control" placeholder="Password" required id="password2">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-8">

                                </div>
                                <!-- /.col -->
                                <div class="col-4">
                                    <button type="button" id="save" class="btn btn-primary btn-block">Save</button>
                                </div>
                                <!-- /.col -->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        save.addEventListener('click', () => {
            if (password1.value == password2.value && password1.value != '' && password2.value != '') {
                password.value = password1.value
                form.submit()
            }
        })
    </script>
@endsection
