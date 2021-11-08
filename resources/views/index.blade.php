
@extends('templates.template')

@section('content')



<body>
    <div class="col-md-12 tabela">
        <div class="col-md-10">
            <div class="novoButton">
                <a href='{{ url("endereco/create") }}' class="btn btn-default">Novo endereço  <span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a class="btn btn-default">
            </div>
            <table class=" table table-bordered">
                <thead>
                    <th>#</th>
                    <th>Logradouro</th>
                    <th>Numero</th>
                    <th>Bairro</th>
                    <th>Municipio</th>
                    <th>Estado</th>
                    <th>Opções</th>
                </thead>
                <tbody>
                    @foreach ($enderecos as $item)
                        
                            <tr id="" >
                                <td>{{ $item->endereco_id }}</td>
                                <td>{{ $item->logradouro }}</td>
                                <td>{{ $item->numero }}</td>
                                <td>{{ $item->bairro }}</td>
                                <td>{{ $item->nome_municipio }}</td>
                                <td>{{ $item->sigla_estado }}</td>
                                <td>
                                    <a href="{{ url('/endereco/edit/')."/".$item->endereco_id }}" href="" class="glyphicon glyphicon-pencil" aria-hidden="true"></a> 
                                    <a href="{{ url('/endereco/delete/')."/".$item->endereco_id }}" class="glyphicon glyphicon-trash" aria-hidden="true"></a>
                                </td>
                            </tr>
                    @endforeach
                     
                </tbody>
            </table>
        </div>
    </div>
</body>
    
    
@endsection