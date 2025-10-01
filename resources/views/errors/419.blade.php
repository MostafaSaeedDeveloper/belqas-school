@extends('errors.template')

@section('code', $status ?? 419)
@section('title', $title ?? 'انتهت صلاحية الجلسة')
@section('message', $message ?? 'تم إيقاف الجلسة لأسباب أمنية. يرجى تحديث الصفحة والمحاولة مرة أخرى.')
