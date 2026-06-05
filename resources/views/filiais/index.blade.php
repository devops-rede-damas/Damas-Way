@extends('layouts.app')

@section('title', 'Filiais')

@section('content')
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h2>Filiais</h2>
            <p>Listagem de filiais sincronizadas do TOTVS RM.</p>
        </div>
        <form action="{{ route('sync.filiais') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-arrow-repeat"></i> Sincronizar com TOTVS
            </button>
        </form>
    </div>

    <div class="card">
        <div class="card-body">
            <table id="tabelaFiliais" class="table table-striped table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>ID RM</th>
                        <th>Filial</th>
                        <th>Coligada</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($filiais as $filial)
                        <tr>
                            <td>{{ $filial->id }}</td>
                            <td>{{ $filial->id_rm }}</td>
                            <td>{{ $filial->filial }}</td>
                            <td>{{ $filial->coligada->coligada ?? '—' }}</td>
                            <td>
                                <div class="form-check form-switch">
                                    <input class="form-check-input toggle-status" type="checkbox"
                                        data-id="{{ $filial->id }}"
                                        {{ $filial->status == 1 ? 'checked' : '' }}>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('js/filiais/filiais.js') }}"></script>
@endpush
