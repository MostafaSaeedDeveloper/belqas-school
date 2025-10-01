@extends('errors.template')

@section('code', $status ?? 503)
@section('title', $title ?? 'الخدمة غير متاحة مؤقتاً')
@section('message', $message ?? 'نقوم بإجراء بعض أعمال الصيانة حالياً. يرجى المحاولة مرة أخرى خلال بضع دقائق.')
