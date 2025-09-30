@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="h3 mb-3">إضافة مادة جديدة</h1>
    <div class="card">
        <form method="post" action="{{ route('subjects.store') }}" class="card-body">
            @include('subjects._form')
        </form>
    </div>
</div>
@endsection
