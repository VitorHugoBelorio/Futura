{{-- filepath: resources/views/funcionarios/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Funcionários</h1>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
        </div>
    @endif

    <div class="d-flex justify-content-end align-items-center gap-2 mb-4">
        <a href="{{ route('gerentes.dashboard') }}" class="btn btn-outline-secondary fw-semibold">Voltar</a>
        <a href="{{ route('funcionarios.create') }}" class="btn btn-primary fw-semibold">Novo Funcionário</a>
        <a href="{{ route('funcionarios.desativados') }}" class="btn btn-warning fw-semibold">Funcionários Desativados</a>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($funcionarios as $funcionario)
                <tr>
                    <td>{{ $funcionario->nome }}</td>
                    <td>{{ $funcionario->email }}</td>
                    <td>
                        <a href="{{ route('funcionarios.edit', $funcionario) }}" class="btn btn-sm btn-warning">Editar</a>
                        <button type="button" 
                                class="btn btn-sm btn-danger btn-delete-funcionario"
                                data-id="{{ $funcionario->id }}"
                                data-nome="{{ $funcionario->nome }}"
                                data-email="{{ $funcionario->email }}">
                            Excluir
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal de Confirmação (Funcionários) -->
<div class="modal fade" id="deleteFuncionarioModal" tabindex="-1" aria-labelledby="deleteFuncionarioLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header bg-danger text-white">
            <h5 class="modal-title" id="deleteFuncionarioLabel">Confirmar Exclusão</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <div class="modal-body">
            <p><strong>Deseja realmente excluir este funcionário?</strong></p>
            <ul class="list-group">
            <li class="list-group-item"><b>Nome:</b> <span id="modalFuncionarioNome"></span></li>
            <li class="list-group-item"><b>Email:</b> <span id="modalFuncionarioEmail"></span></li>
            </ul>
        </div>
        <div class="modal-footer">
            <form id="deleteFuncionarioForm" method="POST">
                @csrf
                @method('DELETE')
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-danger">Excluir</button>
            </form>
        </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const deleteButtons = document.querySelectorAll(".btn-delete-funcionario");
    const modal = new bootstrap.Modal(document.getElementById("deleteFuncionarioModal"));
    const deleteForm = document.getElementById("deleteFuncionarioForm");

    deleteButtons.forEach(button => {
        button.addEventListener("click", function () {
            const id = this.getAttribute("data-id");
            const nome = this.getAttribute("data-nome");
            const email = this.getAttribute("data-email");

            document.getElementById("modalFuncionarioNome").textContent = nome;
            document.getElementById("modalFuncionarioEmail").textContent = email;

            // Ajuste da rota correta
            deleteForm.action = "{{ route('funcionarios.destroy', ':id') }}".replace(':id', id);

            modal.show();
        });
    });

});
</script>

@endsection
