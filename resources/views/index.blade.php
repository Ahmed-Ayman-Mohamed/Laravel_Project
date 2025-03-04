@extends('layouts.header')

<table style="text-align: left">
  <tr>
    <th>id</th>
    <th>Author</th>
    <th>body</th>
  </tr>
  <div>
    @foreach ($posts as $post)
        <tr>
            <td style="color:blue">{{$post->id}}</td>
            <td style="color:red">{{$post->title}}</td>
            <td style="color:green">{{$post->body}}</td>
            <td>
                <a href="{{route('post.edit',$post->id)}}" role='button' style="text-decoration:underline;color:blue">Edit</a>
                <a href="{{route('post.edit',$post->id)}}" role='button' style="text-decoration:underline;color:red">delete</a>
            </td>
        </tr>

        
    @endforeach
  </div>
</table>

@extends('layouts.footer')