@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="h3 mb-3">تعديل المادة</h1>
    <div class="card">
        <form method="post" action="{{ route('subjects.update', $subject) }}" class="card-body">
            @method('put')
            @include('subjects::_form')
        </form>
    </div>
</div>
@endsection
