@extends('errors.template')

@section('code', $status ?? 429)
@section('title', $title ?? 'طلبات كثيرة جداً')
@section('message', $message ?? 'لقد أرسلت العديد من الطلبات في فترة زمنية قصيرة. يرجى الانتظار قليلاً ثم إعادة المحاولة.')
