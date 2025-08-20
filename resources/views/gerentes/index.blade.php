@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Gerentes</h1>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
        </div>
    @endif

    <div class="d-flex justify-content-end gap-4 mb-4">
        <a href="{{ route('gerentes.dashboard') }}" class="btn btn-outline-secondary px-4 py-2 fw-semibold">Voltar</a>
        <a href="{{ route('gerentes.create') }}" class="btn btn-primary px-4 py-2 fw-semibold">Novo Gerente</a>
        <a href="{{ route('gerentes.desativados') }}" class="btn btn-warning px-4 py-2 fw-semibold">Gerentes Desativados</a>
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
            @foreach($gerentes as $gerente)
                <tr>
                    <td>{{ $gerente->nome }}</td>
                    <td>{{ $gerente->email }}</td>
                    <td>
                        <a href="{{ route('gerentes.edit', $gerente) }}" class="btn btn-sm btn-warning">Editar</a>
                        <button type="button" 
                                class="btn btn-sm btn-danger btn-delete-gerente"
                                data-id="{{ $gerente->id }}"
                                data-nome="{{ $gerente->nome }}"
                                data-email="{{ $gerente->email }}">
                            Excluir
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal de Confirmação (Gerentes) -->
<div class="modal fade" id="deleteGerenteModal" tabindex="-1" aria-labelledby="deleteGerenteLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header bg-danger text-white">
            <h5 class="modal-title" id="deleteGerenteLabel">Confirmar Exclusão</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <div class="modal-body">
            <p><strong>Deseja realmente excluir este gerente?</strong></p>
            <ul class="list-group">
            <li class="list-group-item"><b>Nome:</b> <span id="modalGerenteNome"></span></li>
            <li class="list-group-item"><b>Email:</b> <span id="modalGerenteEmail"></span></li>
            </ul>
        </div>
        <div class="modal-footer">
            <form id="deleteGerenteForm" method="POST">
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
    const deleteButtons = document.querySelectorAll(".btn-delete-gerente");
    const modal = new bootstrap.Modal(document.getElementById("deleteGerenteModal"));
    const deleteForm = document.getElementById("deleteGerenteForm");

    deleteButtons.forEach(button => {
        button.addEventListener("click", function () {
            const id = this.getAttribute("data-id");
            const nome = this.getAttribute("data-nome");
            const email = this.getAttribute("data-email");

            // Preenche os dados no modal
            document.getElementById("modalGerenteNome").textContent = nome;
            document.getElementById("modalGerenteEmail").textContent = email;

            // Define a rota do form dinamicamente
            deleteForm.action = "{{ url('gerente/gerentes') }}/" + id;

            // Abre o modal
            modal.show();
        });
    });
});
</script>
@endsection
