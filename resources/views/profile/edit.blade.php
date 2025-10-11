@extends('admin.layouts.master')

@section('title', 'تعديل الملف الشخصي - نظام مدرسة بلقاس')

@section('page-header')
    @section('page-title', 'تعديل الملف الشخصي')
    @section('page-subtitle', 'تحديث بيانات الحساب وكلمة المرور')
    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('profile.show') }}">الملف الشخصي</a></li>
        <li class="breadcrumb-item active">تعديل</li>
    @endsection
@endsection

@section('content')
    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="card shadow-sm">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label fw-bold">الاسم الكامل</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">اسم المستخدم</label>
                    <input type="text" name="username" class="form-control" value="{{ old('username', $user->username) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">البريد الإلكتروني</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" placeholder="example@domain.com">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">رقم الهاتف</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" placeholder="01000000000">
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold">العنوان</label>
                    <input type="text" name="address" class="form-control"
                           value="{{ old('address', optional($user->studentProfile)->address ?? optional($user->teacherProfile)->address) }}"
                           placeholder="العنوان الكامل">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">كلمة المرور الجديدة</label>
                    <input type="password" name="password" class="form-control" placeholder="اتركها فارغة للإبقاء على الحالية">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">تأكيد كلمة المرور</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="إعادة إدخال كلمة المرور">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">الصورة الشخصية</label>
                    <input type="file" name="avatar" class="form-control" accept="image/*">
                    <small class="text-muted">الصيغ المسموح بها: JPG, PNG بحجم لا يتجاوز 2MB.</small>
                </div>
            </div>
        </div>
        <div class="card-footer bg-white text-end">
            <a href="{{ route('profile.show') }}" class="btn btn-light">إلغاء</a>
            <button class="btn btn-primary" type="submit"><i class="fas fa-save me-2"></i>حفظ التغييرات</button>
        </div>
    </form>
@endsection
