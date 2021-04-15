@extends('front.layouts.master')
@section('title', 'iletişim')
@section('bg', 'https://lh3.googleusercontent.com/proxy/G0yvnhvb0naOQO8xpfZjBV0YTZAALsHKU_pF5xdb2gaxnNgxpvn_GwR6Jd1fyf3PQB85FmOXc9XU049w5195caJ-3KGI4tLQQoFMQ_Hs65Ji_4UbncicIxLnimJFqw5E9PeHlnwj81w')
@section('content')

    <div class="col-md-10 mx-auto">

        @if(session('success'))
            <div class="alert alert-success">
                {{session('success')}}
            </div>
        @endif

        @if($errors->any())

            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{$error}}</li>
                    @endforeach
                </ul>
            </div>
            @endif

        <p>CONTACT US</p>
        <form method="post" action="{{route('contact.post')}}">
            @csrf
            <div class="control-group">
                <div class="form-group floating-label-form-group controls">
                    <label>Name</label>
                    <input type="text" class="form-control"  value="{{old('name')}}" placeholder="Name" name="name" required>
                    <p class="help-block text-danger"></p>
                </div>
            </div>
            <div class="control-group">
                <div class="form-group floating-label-form-group controls">
                    <label>Email Address</label>
                    <input type="email" class="form-control" value="{{old('email')}}" placeholder="Email Address" name="email" required>
                    <p class="help-block text-danger"></p>
                </div>
            </div>
            <div class="control-group">
                <div class="form-group col-xs-12 floating-label-form-group controls">
                    <label>Phone Number</label>
                    <input type="tel" class="form-control" value="{{old('number')}}" placeholder="Phone Number" name="number" maxlength="11" minlength="11" required>
                    <p class="help-block text-danger"></p>
                </div>
            </div>
            <div class="control-group">
                <div class="form-group floating-label-form-group controls">
                    <label>Message</label>
                    <textarea rows="5" class="form-control" value="{{old('message')}}" placeholder="Message" name="message" required></textarea>
                    <p class="help-block text-danger"></p>
                </div>
            </div>
            <br>
            <div id="success"></div>
            <button type="submit" class="btn btn-primary" name="submit">Send</button>
        </form>
    </div>

@endsection
