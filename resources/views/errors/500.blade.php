@extends('errors.template')

@section('code', $status ?? 500)
@section('title', $title ?? 'عطل في الخادم')
@section('message', $message ?? 'نأسف، حدث خطأ غير متوقع. يعمل فريقنا على إصلاح المشكلة في أسرع وقت.')

@isset($trace)
    @section('trace')
{{ $trace }}
    @endsection
@endisset
