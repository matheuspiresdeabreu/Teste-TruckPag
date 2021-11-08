
@extends('templates.template')

@section('content')


<body>

    <div class="col-md-12 marginContainer tabela">
                <div class="col-md-10">
                    <div style="border:black 1px solid;border-radius:7px;margin-left:20%" class="   col-md-10"> 
                        <div class="">
                            <h3 class="" id="myModalLabel">Cadastra endereço</h3>
                        </div>
                        <div class=" ">
                        <form action="{{ url('/endereco/update/') }}/{{ $endereco->endereco_id }}" method="POST" class="form">
                            <input type="hidden" name="_method" value="PUT">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class='col-md-12'>
                                <label class='col-md-4'>Logradouro</label>
                                <label class='col-md-6'>Numero</label>
                                <div class='col-md-4'>
                                    <input value="{{ $endereco->logradouro }}" name="logradouro" id="codBarras" class="form-control" type="text"></input>
    
                                </div>
                                <div class='col-md-4'>
                                    <input value="{{ $endereco->numero }}" name="numero" id="codBarras" class="form-control" type="number"></input>
                                </div>
                            </div>
                            <div class='col-md-8'>
                                <label class='col-md-12'>Bairro</label>
                                <div class='col-md-12'>
                                    <input value="{{ $endereco->bairro }}"  name="bairro" id="valorUnitarioProduto" class="form-control" type="text"></input>
    
                                </div>
                            </div>
                       
                           
                            <div class='col-md-12'>
                                <label class='col-md-4'>Cidade</label>
                                <label class='col-md-6'>Estado</label>
                                <div class='col-md-4'>
                                    <select name="municipio_id" id='cidades' class="form-control input-sm">
                                        <option selected="selected" value="{{ $endereco->municipio_id }}">{{ $endereco->nome_municipio }}</option>
                                
                                    </select>
    
                                </div>
                                <div class='col-md-4'>
                                    <select name="estado_id" id='estados' onchange="updateCidade()" class="form-control input-sm">
                                    </select>
                                </div>
                            </div>
                            </div>
                            <button type="submit" class="botaoAdmin pull-right btn btn-success">Aplicar</button>
    
                        </form>
                
                </div>
    
    
                </div>
            </div>
        </div>
    
    </body>
    
    
    <script>
        let request = new XMLHttpRequest()
        request.open("GET","{{ url('endereco/estados') }}");
        request.send();
        var estadoSelect = document.getElementById('estados');
        request.onload = () => {
        if(request.status === 200){
            var response = JSON.parse(request.response)
            response.forEach(result => {

                var option = document.createElement("option")
                option.value = result['estado_id']
                option.text = result['sigla']
                if(result['sigla'] == '{{ $endereco->sigla_estado }}'){
                    option.setAttribute("selected", "selected");
                }
                estadoSelect.appendChild(option)
            });
            
        } else {
        console.log("Page not found")// if link is broken, output will be page not found
        }
        }


        function updateCidade() {



            var cidadeSelect = document.getElementById('cidades');
            document.getElementById('cidades').innerHTML = ""
            var e = document.getElementById("estados");
            var idEstado = e.options[e.selectedIndex].value;

            var request = new XMLHttpRequest()
            request.open("GET",'{{ url("/estados/municipios/") }}/'+idEstado)
            request.send()
            request.onload = () => {
            if(request.status === 200){
                var response = JSON.parse(request.response)
                response.forEach(result => {

                    var option = document.createElement("option")
                    option.value = result['id']
                    option.text = result['nome']
                    cidadeSelect.appendChild(option)
            });

            }
            }
        }
    
    </script>

@endsection