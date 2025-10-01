@php
    $icon = $icon ?? 'fas fa-info-circle';
    $title = $title ?? 'هذه الصفحة قيد التطوير';
    $description = $description ?? 'جارٍ العمل على إكمال المزايا المطلوبة. سيتم تفعيل الصفحة قريباً.';
    $tips = $tips ?? [];
    $actions = $actions ?? [];
@endphp

<div class="card shadow-sm placeholder-card">
    <div class="card-body text-center py-5">
        <div class="placeholder-icon text-primary mb-3">
            <i class="{{ $icon }} fa-3x"></i>
        </div>
        <h4 class="fw-bold mb-3">{{ $title }}</h4>
        <p class="text-muted mb-4">{{ $description }}</p>

        @if(! empty($tips))
            <div class="row justify-content-center mb-4">
                <div class="col-lg-8">
                    <ul class="list-group list-group-flush text-start placeholder-tips">
                        @foreach($tips as $tip)
                            <li class="list-group-item d-flex align-items-start">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <span>{{ $tip }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        @if(! empty($actions))
            <div class="d-flex flex-wrap justify-content-center gap-2">
                @foreach($actions as $action)
                    <a href="{{ $action['href'] ?? '#' }}"
                       class="btn btn-{{ $action['style'] ?? 'primary' }}">
                        @isset($action['icon'])
                            <i class="{{ $action['icon'] }} me-1"></i>
                        @endisset
                        {{ $action['label'] ?? 'عرض' }}
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>
