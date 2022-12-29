<!DOCTYPE html>
<html>
<head>
    <title>Laravel 9 Contact US Form Example - ItSolutionStuff.com</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css" />
</head>
<body>
<div class="container">
    <div class="row mt-5 mb-5">
        <div class="col-10 offset-1 mt-5">
            <div class="card">
                <div class="card-header bg-primary">
                    <h3 class="text-white">Register</h3>
                </div>
                <div class="card-body">

                    @if(Session::has('success'))
                    <div class="alert alert-success">
                        {{Session::get('success')}}
                    </div>
                    @endif

                    <form method="POST" action="{{ route('send.store') }}" id="contactUSForm">
                        {{ csrf_field() }}

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <strong>User name:</strong>
                                    <input type="text" name="username" class="form-control" placeholder="username" value="{{ old('username') }}">
                                    @if ($errors->has('username'))
                                    <span class="text-danger">{{ $errors->first('username') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <strong>Email:</strong>
                                    <input type="text" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}">
                                    @if ($errors->has('email'))
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <strong>Password:</strong>
                                    <input type="password" name="password" class="form-control" placeholder="password" value="{{ old('password') }}">
                                    @if ($errors->has('password'))
                                    <span class="text-danger">{{ $errors->first('password') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-group text-center">
                            <button class="btn btn-success btn-submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
