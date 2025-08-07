@extends('layouts.app')

@section('content')
<div class="container my-4">
    <div class="text-center mb-5">
        <h1 class="fw-bold">Seja bem vindo(a), {{ auth()->user()->nome }}</h1>
        <p class="text-muted">Gerencie contratantes, funcionários e gerentes com facilidade.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 mb-0">Contratantes</h2>
        <div class="btn-group">
            <a href="{{ route('contratantes.create') }}" class="btn btn-primary">Novo Contratante</a>
            <a href="{{ route('funcionarios.index') }}" class="btn btn-outline-secondary">Funcionários</a>
            <a href="{{ route('gerentes.index') }}" class="btn btn-outline-success">Gerentes</a>
        </div>
    </div>

    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
        <table class="table table-hover align-middle">
            <thead class="table-light position-sticky top-0" style="z-index: 1;">
                <tr>
                    <th scope="col">Nome</th>
                    <th scope="col">CNPJ</th>
                    <th scope="col">Email</th>
                    <th scope="col">Telefone</th>
                    <th scope="col" class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($contratantes as $contratante)
                    <tr>
                        <td>{{ $contratante->nome }}</td>
                        <td>{{ $contratante->cnpj }}</td>
                        <td>{{ $contratante->email }}</td>
                        <td>{{ $contratante->telefone }}</td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('contratantes.show', $contratante) }}" class="btn btn-outline-info" title="Visualizar">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('contratantes.edit', $contratante) }}" class="btn btn-outline-warning" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('contratantes.destroy', $contratante) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Tem certeza que deseja excluir?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Excluir">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Nenhum contratante cadastrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
