@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="h3 mb-3">إضافة معلم جديد</h1>
    <div class="card">
        <form method="post" action="{{ route('teachers.store') }}" class="card-body">
            @include('teachers._form')
        </form>
    </div>
</div>
@endsection
