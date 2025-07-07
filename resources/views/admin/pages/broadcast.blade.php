@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h1>Broadcast</h1>
    <form action="" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label>Upload your Image</label>
           <input type="file" id="image"  class="form-control">
        </div>
        <div class="form-group">
            <label for="message">Message</label>
            <textarea name="message" id="editor" class="form-control" height="30em"></textarea>

        </div>
        <button type="submit" class="btn btn-success">Sent Message</button>
    </form>
</div>
@endsection
