@php
    $isEdit = $student && $student->exists;
@endphp

<div class="row g-4">
    <div class="col-12 col-xl-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">البيانات الأساسية</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="student_id" class="form-label">رقم الطالب <span class="text-danger">*</span></label>
                        <input type="text" id="student_id" name="student_id" class="form-control" value="{{ old('student_id', $student->student_id) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label for="admission_number" class="form-label">رقم القيد</label>
                        <input type="text" id="admission_number" name="admission_number" class="form-control" value="{{ old('admission_number', $student->admission_number) }}">
                    </div>
                    <div class="col-md-4">
                        <label for="user_id" class="form-label">حساب النظام</label>
                        <select id="user_id" name="user_id" class="form-select">
                            <option value="">بدون ربط</option>
                            @foreach($studentUsers as $user)
                                <option value="{{ $user->id }}" @selected(old('user_id', $student->user_id) == $user->id)>
                                    {{ $user->name }} ({{ $user->username }})
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">اختياري - اربط الطالب بحساب مستخدم يمتلك دور طالب.</small>
                    </div>
                    <div class="col-md-4">
                        <label for="first_name" class="form-label">الاسم الأول <span class="text-danger">*</span></label>
                        <input type="text" id="first_name" name="first_name" class="form-control" value="{{ old('first_name', $student->first_name) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label for="last_name" class="form-label">اسم العائلة <span class="text-danger">*</span></label>
                        <input type="text" id="last_name" name="last_name" class="form-control" value="{{ old('last_name', $student->last_name) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label for="full_name" class="form-label">الاسم الكامل</label>
                        <input type="text" id="full_name" name="full_name" class="form-control" value="{{ old('full_name', $student->full_name) }}">
                    </div>
                    <div class="col-md-4">
                        <label for="gender" class="form-label">النوع <span class="text-danger">*</span></label>
                        <select id="gender" name="gender" class="form-select" required>
                            <option value="male" @selected(old('gender', $student->gender) === 'male')>ذكر</option>
                            <option value="female" @selected(old('gender', $student->gender) === 'female')>أنثى</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="date_of_birth" class="form-label">تاريخ الميلاد</label>
                        <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', optional($student->date_of_birth)->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-4">
                        <label for="nationality" class="form-label">الجنسية</label>
                        <input type="text" id="nationality" name="nationality" class="form-control" value="{{ old('nationality', $student->nationality) }}">
                    </div>
                    <div class="col-md-4">
                        <label for="national_id" class="form-label">رقم الهوية</label>
                        <input type="text" id="national_id" name="national_id" class="form-control" value="{{ old('national_id', $student->national_id) }}">
                    </div>
                    <div class="col-md-4">
                        <label for="passport_number" class="form-label">رقم الباسبور</label>
                        <input type="text" id="passport_number" name="passport_number" class="form-control" value="{{ old('passport_number', $student->passport_number) }}">
                    </div>
                    <div class="col-md-4">
                        <label for="blood_type" class="form-label">فصيلة الدم</label>
                        <input type="text" id="blood_type" name="blood_type" class="form-control" value="{{ old('blood_type', $student->blood_type) }}" placeholder="A+">
                    </div>
                    <div class="col-md-4">
                        <label for="religion" class="form-label">الديانة</label>
                        <input type="text" id="religion" name="religion" class="form-control" value="{{ old('religion', $student->religion) }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">بيانات الاتصال والعنوان</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="phone" class="form-label">رقم الجوال</label>
                        <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone', $student->phone) }}">
                    </div>
                    <div class="col-md-4">
                        <label for="email" class="form-label">البريد الإلكتروني</label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $student->email) }}">
                    </div>
                    <div class="col-md-4">
                        <label for="country" class="form-label">الدولة</label>
                        <input type="text" id="country" name="country" class="form-control" value="{{ old('country', $student->country) }}">
                    </div>
                    <div class="col-md-6">
                        <label for="city" class="form-label">المدينة</label>
                        <input type="text" id="city" name="city" class="form-control" value="{{ old('city', $student->city) }}">
                    </div>
                    <div class="col-md-6">
                        <label for="state" class="form-label">المحافظة / المنطقة</label>
                        <input type="text" id="state" name="state" class="form-control" value="{{ old('state', $student->state) }}">
                    </div>
                    <div class="col-md-12">
                        <label for="address" class="form-label">العنوان</label>
                        <input type="text" id="address" name="address" class="form-control" value="{{ old('address', $student->address) }}">
                    </div>
                    <div class="col-md-4">
                        <label for="postal_code" class="form-label">الرمز البريدي</label>
                        <input type="text" id="postal_code" name="postal_code" class="form-control" value="{{ old('postal_code', $student->postal_code) }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">بيانات ولي الأمر</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="guardian_name" class="form-label">اسم ولي الأمر</label>
                        <input type="text" id="guardian_name" name="guardian_name" class="form-control" value="{{ old('guardian_name', $student->guardian_name) }}">
                    </div>
                    <div class="col-md-4">
                        <label for="guardian_relation" class="form-label">صلة القرابة</label>
                        <input type="text" id="guardian_relation" name="guardian_relation" class="form-control" value="{{ old('guardian_relation', $student->guardian_relation) }}" placeholder="أب، أم، أخ...">
                    </div>
                    <div class="col-md-4">
                        <label for="guardian_phone" class="form-label">رقم الجوال</label>
                        <input type="text" id="guardian_phone" name="guardian_phone" class="form-control" value="{{ old('guardian_phone', $student->guardian_phone) }}">
                    </div>
                    <div class="col-md-6">
                        <label for="guardian_email" class="form-label">البريد الإلكتروني</label>
                        <input type="email" id="guardian_email" name="guardian_email" class="form-control" value="{{ old('guardian_email', $student->guardian_email) }}">
                    </div>
                    <div class="col-md-6">
                        <label for="guardian_address" class="form-label">عنوان ولي الأمر</label>
                        <input type="text" id="guardian_address" name="guardian_address" class="form-control" value="{{ old('guardian_address', $student->guardian_address) }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">البيانات الأكاديمية</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="admission_date" class="form-label">تاريخ الالتحاق</label>
                        <input type="date" id="admission_date" name="admission_date" class="form-control" value="{{ old('admission_date', optional($student->admission_date)->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-4">
                        <label for="grade_id" class="form-label">الصف الدراسي</label>
                        <select id="grade_id" name="grade_id" class="form-select">
                            <option value="">اختر الصف</option>
                            @foreach($grades as $grade)
                                <option value="{{ $grade->id }}" @selected(old('grade_id', $student->grade_id) == $grade->id)>{{ $grade->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="class_id" class="form-label">الفصل</label>
                        <select id="class_id" name="class_id" class="form-select">
                            <option value="">اختر الفصل</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" @selected(old('class_id', $student->class_id) == $class->id)>{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="section_id" class="form-label">الشعبة</label>
                        <select id="section_id" name="section_id" class="form-select">
                            <option value="">اختر الشعبة</option>
                            @foreach($sections as $section)
                                <option value="{{ $section->id }}" @selected(old('section_id', $student->section_id) == $section->id)>{{ $section->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="roll_number" class="form-label">رقم الجلوس</label>
                        <input type="text" id="roll_number" name="roll_number" class="form-control" value="{{ old('roll_number', $student->roll_number) }}">
                    </div>
                    <div class="col-md-4">
                        <label for="academic_year" class="form-label">السنة الدراسية</label>
                        <input type="text" id="academic_year" name="academic_year" class="form-control" value="{{ old('academic_year', $student->academic_year) }}" placeholder="2024/2025">
                    </div>
                    <div class="col-md-4">
                        <label for="status" class="form-label">حالة القيد <span class="text-danger">*</span></label>
                        <select id="status" name="status" class="form-select" required>
                            @foreach($statuses as $statusKey => $statusLabel)
                                <option value="{{ $statusKey }}" @selected(old('status', $student->status ?? 'enrolled') === $statusKey)>{{ $statusLabel }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">بيانات إضافية</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="transportation" class="form-label">وسيلة المواصلات</label>
                        <input type="text" id="transportation" name="transportation" class="form-control" value="{{ old('transportation', $student->transportation) }}" placeholder="باص المدرسة">
                    </div>
                    <div class="col-md-6">
                        <label for="previous_school" class="form-label">المدرسة السابقة</label>
                        <input type="text" id="previous_school" name="previous_school" class="form-control" value="{{ old('previous_school', $student->previous_school) }}">
                    </div>
                    <div class="col-md-12">
                        <label for="medical_info" class="form-label">ملاحظات طبية</label>
                        <textarea id="medical_info" name="medical_info" rows="3" class="form-control">{{ old('medical_info', $student->medical_info) }}</textarea>
                    </div>
                    <div class="col-md-12">
                        <label for="notes" class="form-label">ملاحظات عامة</label>
                        <textarea id="notes" name="notes" rows="3" class="form-control">{{ old('notes', $student->notes) }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">صورة الطالب</h5>
            </div>
            <div class="card-body text-center">
                @if($isEdit && $student->profile_photo_url)
                    <img src="{{ $student->profile_photo_url }}" alt="صورة الطالب" class="img-fluid rounded mb-3" style="max-height: 220px;">
                @else
                    <div class="avatar-placeholder mb-3">
                        <i class="fas fa-user-graduate fa-4x text-muted"></i>
                    </div>
                @endif
                <input type="file" name="profile_photo" class="form-control">
                <small class="text-muted d-block mt-2">الصيغ المسموحة: JPG, PNG - الحجم الأقصى 2 ميجابايت.</small>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save ms-1"></i>
                        حفظ البيانات
                    </button>
                    <a href="{{ route('students.index') }}" class="btn btn-light">
                        العودة لقائمة الطلاب
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
