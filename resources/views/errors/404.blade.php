@extends('errors.template')

@section('code', $status ?? 404)
@section('title', $title ?? 'الصفحة غير موجودة')
@section('message', $message ?? 'يبدو أن الرابط الذي تحاول الوصول إليه غير صحيح أو ربما تم نقله.')
