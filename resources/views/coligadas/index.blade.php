@extends('layouts.app')

@section('title', 'Coligadas')

@section('content')
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h2>Coligadas</h2>
            <p>Listagem de coligadas sincronizadas do TOTVS RM.</p>
        </div>
        <form action="{{ route('sync.coligadas') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-arrow-repeat"></i> Sincronizar com TOTVS
            </button>
        </form>
    </div>

    <div class="card">
        <div class="card-body">
            <table id="tabelaColigadas" class="table table-striped table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>ID RM</th>
                        <th>Coligada</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($coligadas as $coligada)
                        <tr>
                            <td>{{ $coligada->id }}</td>
                            <td>{{ $coligada->id_rm }}</td>
                            <td>{{ $coligada->coligada }}</td>
                            <td>
                                <div class="form-check form-switch">
                                    <input class="form-check-input toggle-status" type="checkbox"
                                        data-id="{{ $coligada->id }}"
                                        {{ $coligada->status == 1 ? 'checked' : '' }}>
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
<script src="{{ asset('js/coligadas/coligadas.js') }}"></script>
@endpush
