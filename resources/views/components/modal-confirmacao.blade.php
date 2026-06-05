@props(['id' => 'modalConfirmacao', 'title' => 'Confirmação', 'message' => 'Tem certeza que deseja realizar esta ação?', 'confirmText' => 'Confirmar', 'confirmClass' => 'btn-primary', 'icon' => 'exclamation-circle'])

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center py-4 px-4">
                <div class="mb-3">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 56px; height: 56px; background: #fff3cd;">
                        <i class="bi bi-{{ $icon }} text-warning" style="font-size: 1.5rem;"></i>
                    </div>
                </div>
                <h6 class="fw-semibold mb-2" id="{{ $id }}Label">{{ $title }}</h6>
                <p class="mb-0 text-muted small" id="{{ $id }}Msg">{{ $message }}</p>
            </div>
            <div class="modal-footer border-top-0 justify-content-center pb-4 pt-0 gap-2">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn {{ $confirmClass }} px-4" id="{{ $id }}Btn">{{ $confirmText }}</button>
            </div>
        </div>
    </div>
</div>
