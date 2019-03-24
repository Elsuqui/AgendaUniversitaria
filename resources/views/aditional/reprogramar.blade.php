@extends("layouts.app")

@section("content")
    <reprogramar-eventos :evento="{{ json_encode($evento)  }}" ></reprogramar-eventos>
@endsection

@section("js")

@endsection
