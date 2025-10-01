@extends('errors.template')

@section('code', $status ?? 500)
@section('title', $title ?? 'حدث خطأ غير متوقع')
@section('message', $message ?? 'نأسف، حدث خطأ غير متوقع. يرجى المحاولة لاحقاً.')

@isset($trace)
    @section('trace')
{{ $trace }}
    @endsection
@endisset
