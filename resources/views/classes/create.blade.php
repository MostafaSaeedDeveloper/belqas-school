@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="h3 mb-3">إضافة فصل جديد</h1>
    <div class="card">
        <form method="post" action="{{ route('classes.store') }}" class="card-body">
            @include('classes._form')
        </form>
    </div>
</div>
@endsection
