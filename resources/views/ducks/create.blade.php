@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create your new Duck</div>

                <div class="card-body">
                    @if (count($errors))
                        <div class="form-group">
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{$error}}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    @if(session()->has('message'))
                        <div class="alert alert-success">
                            {{ session()->get('message') }}
                        </div>
                    @endif

                    @if ( $has_duck ) 
                        <p>You already have a living duck. Click <a href="{{ url('/ducks') }}">here</a> to view your duck.</p>
                    @else 
                        <form id="duck" action="{{ url('/ducks') }}" method="POST" autocomplete="off">
                            @csrf
                            <input type="hidden" name="id" class="form-control" value="{{ isset($duck->id) ? $duck->id : '' }}"/>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">What is your Ducks name?</label>
                                        <input class="form-control" name="name" id="name" type="text" value="{{ isset($duck->name) ? $duck->name : '' }}" required/>
                                    </div>
                                </div>
                            </div>

                            <div class="float-right">
                                <button id="save" class="btn btn-success" type="submit">Save</button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection