@php
    $isEdit = isset($class) && $class->exists;
    $sectionsData = collect(old('sections', isset($sections)
        ? $sections->map(fn ($section) => [
            'id' => $section->id,
            'name' => $section->name,
            'code' => $section->code,
            'description' => $section->description,
        ])->toArray()
        : []));

    if ($sectionsData->isEmpty()) {
        $sectionsData = collect([[
            'id' => null,
            'name' => '',
            'code' => '',
            'description' => '',
        ]]);
    }
@endphp

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">البيانات الأساسية للفصل</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">اسم الفصل <span class="text-danger">*</span></label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $class->name) }}" required>
                        @error('name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="code" class="form-label">رمز الفصل</label>
                        <input type="text" id="code" name="code" class="form-control" value="{{ old('code', $class->code) }}" placeholder="1A">
                        @error('code')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="grade_id" class="form-label">الصف الدراسي</label>
                        <select id="grade_id" name="grade_id" class="form-select">
                            <option value="">اختر الصف</option>
                            @foreach($grades as $grade)
                                <option value="{{ $grade->id }}" @selected(old('grade_id', $class->grade_id) == $grade->id)>{{ $grade->name }}</option>
                            @endforeach
                        </select>
                        @error('grade_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="capacity" class="form-label">السعة القصوى للطلاب</label>
                        <input type="number" id="capacity" name="capacity" class="form-control" value="{{ old('capacity', $class->capacity) }}" min="1" max="200">
                        @error('capacity')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label for="description" class="form-label">ملاحظات عن الفصل</label>
                        <textarea id="description" name="description" class="form-control" rows="3" placeholder="اكتب وصفًا مختصرًا عن الفصل أو الاستخدام الخاص">{{ old('description', $class->description) }}</textarea>
                        @error('description')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">معلومات إضافية</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0 text-muted small">
                    <li>• يفضل استخدام أسماء وأكواد موحدة للفصول لتسهيل البحث والتقارير.</li>
                    <li>• يمكنك إضافة أكثر من شعبة للفصل الواحد من خلال لوحة "الشعب" في الجهة اليمنى.</li>
                    <li>• سيتم استخدام السعة القصوى عند حساب نسب الإشغال وعدد الطلاب.</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">الشعب المرتبطة بالفصل</h5>
                <button type="button" class="btn btn-sm btn-outline-primary" id="add-section-row">
                    <i class="fas fa-plus ms-1"></i> إضافة شعبة
                </button>
            </div>
            <div class="card-body">
                <p class="text-muted small">يمكنك إنشاء الشعب الداخلية لهذا الفصل لتوزيع الطلاب أو الجداول. اترك الحقول فارغة لحذف الشعبة.</p>
                <div id="sections-repeater">
                    @foreach($sectionsData as $index => $section)
                        <div class="section-item border rounded-3 p-3 mb-3">
                            <input type="hidden" name="sections[{{ $index }}][id]" value="{{ $section['id'] ?? '' }}">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-5">
                                    <label class="form-label">اسم الشعبة</label>
                                    <input type="text" class="form-control" name="sections[{{ $index }}][name]" value="{{ $section['name'] ?? '' }}" placeholder="الشعبة أ">
                                    @error('sections.' . $index . '.name')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">رمز الشعبة</label>
                                    <input type="text" class="form-control" name="sections[{{ $index }}][code]" value="{{ $section['code'] ?? '' }}" placeholder="S1A">
                                    @error('sections.' . $index . '.code')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3 text-md-end">
                                    <button type="button" class="btn btn-outline-danger remove-section">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">ملاحظات</label>
                                    <input type="text" class="form-control" name="sections[{{ $index }}][description]" value="{{ $section['description'] ?? '' }}" placeholder="وصف مختصر للشعبة">
                                    @error('sections.' . $index . '.description')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="alert alert-info bg-opacity-10 border-0 text-info">
                    يمكنك سحب وإسقاط الطلاب على الشعب لاحقًا من خلال واجهة إدارة الطلاب.
                </div>
            </div>
        </div>
    </div>
</div>

<div id="section-template" class="d-none">
    <div class="section-item border rounded-3 p-3 mb-3">
        <input type="hidden" name="sections[__INDEX__][id]" value="">
        <div class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label">اسم الشعبة</label>
                <input type="text" class="form-control" name="sections[__INDEX__][name]" placeholder="الشعبة أ">
            </div>
            <div class="col-md-4">
                <label class="form-label">رمز الشعبة</label>
                <input type="text" class="form-control" name="sections[__INDEX__][code]" placeholder="S1A">
            </div>
            <div class="col-md-3 text-md-end">
                <button type="button" class="btn btn-outline-danger remove-section">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="col-12">
                <label class="form-label">ملاحظات</label>
                <input type="text" class="form-control" name="sections[__INDEX__][description]" placeholder="وصف مختصر للشعبة">
            </div>
        </div>
    </div>
</div>

@once
    @push('inline-scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const addSectionButton = document.getElementById('add-section-row');
                const container = document.getElementById('sections-repeater');
                const template = document.getElementById('section-template');

                if (!addSectionButton || !container || !template) {
                    return;
                }

                addSectionButton.addEventListener('click', function () {
                    const index = container.querySelectorAll('.section-item').length;
                    const html = template.innerHTML.replace(/__INDEX__/g, index);
                    container.insertAdjacentHTML('beforeend', html);
                });

                container.addEventListener('click', function (event) {
                    if (event.target.closest('.remove-section')) {
                        const item = event.target.closest('.section-item');
                        if (item) {
                            item.remove();
                        }
                    }
                });
            });
        </script>
    @endpush
@endonce
