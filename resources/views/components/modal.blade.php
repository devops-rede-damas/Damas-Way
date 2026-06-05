@props(['id', 'title', 'icon' => '', 'size' => ''])

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered {{ $size }}">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title fw-semibold" id="{{ $id }}Label">
                    @if($icon)<i class="bi bi-{{ $icon }} me-2" style="color: var(--fp-accent);"></i>@endif{{ $title }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            {{ $slot }}
        </div>
    </div>
</div>
