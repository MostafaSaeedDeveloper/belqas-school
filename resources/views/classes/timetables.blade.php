@extends('admin.layouts.master')

@section('title', 'الجداول الدراسية للفصول')
@section('page-title', 'الجداول الدراسية للفصول')
@section('page-subtitle', 'إدارة توزيع الحصص اليومية لكل فصل وشعبة')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('classes.index') }}">الفصول الدراسية</a></li>
    <li class="breadcrumb-item active" aria-current="page">الجداول الدراسية</li>
@endsection

@section('content')
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('classes.timetables') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-5 col-lg-4">
                    <label for="class_id" class="form-label">اختر الفصل الدراسي</label>
                    <select name="class_id" id="class_id" class="form-select">
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" @selected($selectedClassId == $class->id)>
                                {{ $class->name }} @if($class->grade) - {{ $class->grade->name }} @endif
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 col-lg-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-eye ms-1"></i>
                        عرض الجدول
                    </button>
                </div>
                <div class="col-md-4 col-lg-6 text-md-end">
                    @can('create_classes')
                        <a href="{{ route('classes.create') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-door-open ms-1"></i>
                            إضافة فصل جديد
                        </a>
                    @endcan
                </div>
            </form>
        </div>
    </div>

    @if($selectedClass)
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">ملخص الفصل المختار</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-2"><strong>اسم الفصل:</strong> {{ $selectedClass->name }}</p>
                        <p class="mb-2"><strong>الصف الدراسي:</strong> {{ optional($selectedClass->grade)->name ?? 'غير محدد' }}</p>
                        <p class="mb-2"><strong>عدد الشعب:</strong> {{ $selectedClass->sections->count() }}</p>
                        <p class="mb-0"><strong>السعة القصوى:</strong> {{ $selectedClass->capacity ? $selectedClass->capacity . ' طالب' : 'غير محددة' }}</p>
                    </div>
                </div>

                @can('edit_classes')
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">إضافة حصة جديدة</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('classes.timetables.store') }}" method="POST" class="d-grid gap-3">
                                @csrf
                                <input type="hidden" name="class_id" value="{{ $selectedClass->id }}">

                                <div>
                                    <label for="day_of_week" class="form-label">اليوم</label>
                                    <select name="day_of_week" id="day_of_week" class="form-select">
                                        @foreach($days as $dayKey => $dayLabel)
                                            <option value="{{ $dayKey }}" @selected(old('day_of_week') === $dayKey)>{{ $dayLabel }}</option>
                                        @endforeach
                                    </select>
                                    @error('day_of_week')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row g-2">
                                    <div class="col-6">
                                        <label for="period" class="form-label">رقم الحصة</label>
                                        <input type="number" name="period" id="period" class="form-control" value="{{ old('period') }}" min="1" max="12">
                                        @error('period')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-6">
                                        <label for="section_id" class="form-label">الشعبة</label>
                                        <select name="section_id" id="section_id" class="form-select">
                                            <option value="">بدون تحديد</option>
                                            @foreach($sections as $section)
                                                <option value="{{ $section->id }}" @selected(old('section_id') == $section->id)>{{ $section->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('section_id')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div>
                                    <label for="subject" class="form-label">المادة الدراسية</label>
                                    <input type="text" name="subject" id="subject" class="form-control" value="{{ old('subject') }}" placeholder="مثل: اللغة العربية" required>
                                    @error('subject')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label for="teacher_name" class="form-label">اسم المعلم</label>
                                    <input type="text" name="teacher_name" id="teacher_name" class="form-control" value="{{ old('teacher_name') }}" placeholder="اسم المعلم المسؤول">
                                    @error('teacher_name')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row g-2">
                                    <div class="col-6">
                                        <label for="start_time" class="form-label">بداية الحصة</label>
                                        <input type="time" name="start_time" id="start_time" class="form-control" value="{{ old('start_time') }}">
                                        @error('start_time')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-6">
                                        <label for="end_time" class="form-label">نهاية الحصة</label>
                                        <input type="time" name="end_time" id="end_time" class="form-control" value="{{ old('end_time') }}">
                                        @error('end_time')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div>
                                    <label for="notes" class="form-label">ملاحظات</label>
                                    <textarea name="notes" id="notes" class="form-control" rows="2" placeholder="ملاحظات إضافية عن الحصة">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-plus-circle ms-1"></i>
                                    إضافة إلى الجدول
                                </button>
                            </form>
                        </div>
                    </div>
                @endcan
            </div>

            <div class="col-lg-8">
                @foreach($days as $dayKey => $dayLabel)
                    @php
                        $dayEntries = $timetables->get($dayKey, collect());
                    @endphp
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">{{ $dayLabel }}</h5>
                            <span class="badge bg-secondary bg-opacity-10 text-secondary">{{ $dayEntries->count() }} حصة</span>
                        </div>
                        <div class="card-body p-0">
                            @if($dayEntries->isNotEmpty())
                                <div class="list-group list-group-flush">
                                    @foreach($dayEntries as $entry)
                                        @php
                                            $startTime = $entry->start_time ? substr($entry->start_time, 0, 5) : null;
                                            $endTime = $entry->end_time ? substr($entry->end_time, 0, 5) : null;
                                        @endphp
                                        <div class="list-group-item">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="mb-1">الحصة رقم {{ $entry->period }} - {{ $entry->subject }}</h6>
                                                    <div class="text-muted small d-flex flex-wrap gap-3">
                                                        @if($entry->section)
                                                            <span><i class="fas fa-layer-group ms-1"></i> {{ $entry->section->name }}</span>
                                                        @endif
                                                        @if($entry->teacher_name)
                                                            <span><i class="fas fa-chalkboard-teacher ms-1"></i> {{ $entry->teacher_name }}</span>
                                                        @endif
                                                        @if($startTime || $endTime)
                                                            <span><i class="fas fa-clock ms-1"></i> {{ $startTime ?? 'غير محدد' }} @if($endTime) - {{ $endTime }} @endif</span>
                                                        @endif
                                                        @if($entry->room)
                                                            <span><i class="fas fa-door-closed ms-1"></i> {{ $entry->room }}</span>
                                                        @endif
                                                    </div>
                                                    @if($entry->notes)
                                                        <p class="text-muted small mb-0 mt-2">{{ $entry->notes }}</p>
                                                    @endif
                                                </div>
                                                @can('edit_classes')
                                                    <div class="btn-group">
                                                        <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#timetable-edit-{{ $entry->id }}" aria-expanded="false">
                                                            تعديل
                                                        </button>
                                                        <form action="{{ route('classes.timetables.destroy', $entry) }}" method="POST" onsubmit="return confirm('هل تريد حذف هذه الحصة من الجدول؟');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger">حذف</button>
                                                        </form>
                                                    </div>
                                                @endcan
                                            </div>

                                            @can('edit_classes')
                                                <div class="collapse mt-3" id="timetable-edit-{{ $entry->id }}">
                                                    <form action="{{ route('classes.timetables.update', $entry) }}" method="POST" class="row g-3">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="class_id" value="{{ $entry->class_id }}">

                                                        <div class="col-md-4">
                                                            <label class="form-label">اليوم</label>
                                                            <select name="day_of_week" class="form-select">
                                                                @foreach($days as $dayKeyOption => $dayLabelOption)
                                                                    <option value="{{ $dayKeyOption }}" @selected($entry->day_of_week === $dayKeyOption)>{{ $dayLabelOption }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label class="form-label">رقم الحصة</label>
                                                            <input type="number" name="period" class="form-control" value="{{ $entry->period }}" min="1" max="12">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label">الشعبة</label>
                                                            <select name="section_id" class="form-select">
                                                                <option value="">بدون تحديد</option>
                                                                @foreach($sections as $section)
                                                                    <option value="{{ $section->id }}" @selected($entry->section_id == $section->id)>{{ $section->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label">الغرفة</label>
                                                            <input type="text" name="room" class="form-control" value="{{ $entry->room }}" placeholder="غرفة أو معمل">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">المادة</label>
                                                            <input type="text" name="subject" class="form-control" value="{{ $entry->subject }}" required>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">اسم المعلم</label>
                                                            <input type="text" name="teacher_name" class="form-control" value="{{ $entry->teacher_name }}">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label">بداية الحصة</label>
                                                            <input type="time" name="start_time" class="form-control" value="{{ $entry->start_time ? substr($entry->start_time, 0, 5) : '' }}">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label">نهاية الحصة</label>
                                                            <input type="time" name="end_time" class="form-control" value="{{ $entry->end_time ? substr($entry->end_time, 0, 5) : '' }}">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">ملاحظات</label>
                                                            <input type="text" name="notes" class="form-control" value="{{ $entry->notes }}">
                                                        </div>
                                                        <div class="col-12 d-flex justify-content-end">
                                                            <button type="submit" class="btn btn-success">
                                                                <i class="fas fa-save ms-1"></i> حفظ التعديلات
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            @endcan
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="p-4 text-center text-muted">لا توجد حصص مجدولة لهذا اليوم.</div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="alert alert-info">
            لا توجد فصول مسجلة بعد. قم بإضافة فصل جديد لبدء إنشاء الجداول الدراسية.
        </div>
    @endif
@endsection
