@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="h3 mb-3">تعديل الفصل</h1>
    <div class="card">
        <form method="post" action="{{ route('classes.update', $class) }}" class="card-body">
            @method('put')
            @include('classes::_form')
        </form>
    </div>
</div>
@endsection
