@extends('errors.template')

@section('code', $status ?? 403)
@section('title', $title ?? 'غير مصرح لك بالوصول')
@section('message', $message ?? 'نأسف، ليس لديك الإذن الكافي لعرض هذه الصفحة.')
