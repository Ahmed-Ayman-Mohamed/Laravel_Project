@extends('layouts.header')


@section('title')
    Post page
@endsection

    @extends('layouts.nav')
    <div>
        <form action='{{route('post.insert')}}' method="post">
            @csrf
            <input type="text" name="title" placeholder="Enter Title"><br>
            <input type="text" name="body" placeholder="Enter Body"><br>
            <button type='submit'>Submit</button>
        </form>

    </div>


@extends('layouts.footer')
