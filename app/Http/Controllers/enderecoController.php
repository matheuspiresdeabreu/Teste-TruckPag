<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\modelEndereco;



function Utf8_ansi($valor='') {

    $utf8_ansi2 = array(
    "\u00c0" =>"À",
    "\u00c1" =>"Á",
    "\u00c2" =>"Â",
    "\u00c3" =>"Ã",
    "\u00c4" =>"Ä",
    "\u00c5" =>"Å",
    "\u00c6" =>"Æ",
    "\u00c7" =>"Ç",
    "\u00c8" =>"È",
    "\u00c9" =>"É",
    "\u00ca" =>"Ê",
    "\u00cb" =>"Ë",
    "\u00cc" =>"Ì",
    "\u00cd" =>"Í",
    "\u00ce" =>"Î",
    "\u00cf" =>"Ï",
    "\u00d1" =>"Ñ",
    "\u00d2" =>"Ò",
    "\u00d3" =>"Ó",
    "\u00d4" =>"Ô",
    "\u00d5" =>"Õ",
    "\u00d6" =>"Ö",
    "\u00d8" =>"Ø",
    "\u00d9" =>"Ù",
    "\u00da" =>"Ú",
    "\u00db" =>"Û",
    "\u00dc" =>"Ü",
    "\u00dd" =>"Ý",
    "\u00df" =>"ß",
    "\u00e0" =>"à",
    "\u00e1" =>"á",
    "\u00e2" =>"â",
    "\u00e3" =>"ã",
    "\u00e4" =>"ä",
    "\u00e5" =>"å",
    "\u00e6" =>"æ",
    "\u00e7" =>"ç",
    "\u00e8" =>"è",
    "\u00e9" =>"é",
    "\u00ea" =>"ê",
    "\u00eb" =>"ë",
    "\u00ec" =>"ì",
    "\u00ed" =>"í",
    "\u00ee" =>"î",
    "\u00ef" =>"ï",
    "\u00f0" =>"ð",
    "\u00f1" =>"ñ",
    "\u00f2" =>"ò",
    "\u00f3" =>"ó",
    "\u00f4" =>"ô",
    "\u00f5" =>"õ",
    "\u00f6" =>"ö",
    "\u00f8" =>"ø",
    "\u00f9" =>"ù",
    "\u00fa" =>"ú",
    "\u00fb" =>"û",
    "\u00fc" =>"ü",
    "\u00fd" =>"ý",
    "\u00ff" =>"ÿ");

    return strtr($valor, $utf8_ansi2);      

}

class enderecoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        if(sizeof(DB::select('select * from estados')) != 27){
            $client = new Client();
            $response = $client->request('GET', "https://servicodados.ibge.gov.br/api/v1/localidades/estados");
    
            $responseBody = json_decode($response->getBody());
            foreach($responseBody as $uf){
                DB::insert('insert into estados (estado_id,nome, sigla) values (?, ?, ?)', [$uf->id,$uf->nome, $uf->sigla]);
            }
        }
        $enderecos = DB::select('SELECT `endereco_id`, `logradouro`, `numero`, `bairro`, `municipio_id`, (SELECT `nome` FROM `municipios` WHERE municipios.municipio_id = enderecos.municipio_id) as `nome_municipio`, ( SELECT `estado_id` FROM `municipios` WHERE `municipio_id`=`enderecos`.`municipio_id`) as `fk_estado_id`, (SELECT `sigla` FROM `estados` WHERE `estado_id`=`fk_estado_id`) as `sigla_estado` FROM `enderecos`');
        return view("index",['enderecos'=>$enderecos]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("formEndereco");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if(sizeof(DB::select('select * from municipios where municipio_id = ?', [$request->get("municipio_id")])) == 0){
            $client = new Client();
            $response = $client->request('GET', "https://servicodados.ibge.gov.br/api/v1/localidades/municipios/".$request->get("municipio_id"));
    
            $responseBody = json_decode($response->getBody());

            DB::table('municipios')->insert([
                'municipio_id'=>$responseBody->id,
                'nome'=>$responseBody->nome,
                'estado_id'=>$responseBody->microrregiao->mesorregiao->UF->id
            ]);
        }

        DB::table('enderecos')->insert([
            'logradouro'=>$request->get("logradouro"),
            'numero'=>$request->get("numero"),
            'bairro'=>$request->get("bairro"),
            'municipio_id'=>$request->get("municipio_id")
        
        ]);

        return redirect("/");
        
    }

 

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $info = DB::select('SELECT `endereco_id`, `logradouro`, `numero`, `bairro`, `municipio_id`, (SELECT `nome` FROM `municipios` WHERE municipios.municipio_id = enderecos.municipio_id) as `nome_municipio`, ( SELECT `estado_id` FROM `municipios` WHERE `municipio_id`=`enderecos`.`municipio_id`) as `fk_estado_id`, (SELECT `sigla` FROM `estados` WHERE `estado_id`=`fk_estado_id`) as `sigla_estado` FROM `enderecos` WHERE endereco_id = ?', [$id])[0];
        return view("alteraEndereco",['endereco'=>$info]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(sizeof(DB::select('select * from municipios where municipio_id = ?', [$request->get("municipio_id")])) == 0){
            $client = new Client();
            $response = $client->request('GET', "https://servicodados.ibge.gov.br/api/v1/localidades/municipios/".$request->get("municipio_id"));
    
            $responseBody = json_decode($response->getBody());
            DB::table('municipios')->insert([
                'municipio_id'=>$responseBody->id,
                'nome'=>$responseBody->nome,
                'estado_id'=>$responseBody->microrregiao->mesorregiao->UF->id
            ]);
        }
        DB::table('enderecos')->where('endereco_id',$id)->update([
            'logradouro'=>$request->get("logradouro"),
            'numero'=>$request->get("numero"),
            'bairro'=>$request->get("bairro"),
            'municipio_id'=>$request->get("municipio_id")]);
        return redirect("/");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('enderecos')->where('endereco_id','=',$id)->delete();
        return redirect("/");

    }

    public function showMunicipiosDoEstado($iduf)
    {
            $client = new Client();
            $response = $client->request('GET', "https://servicodados.ibge.gov.br/api/v1/localidades/estados/$iduf/municipios");
            return $response->getBody();


    }


    public function showMunicipio($id)
    {
        $client = new Client();
        $response = $client->request('GET', "https://servicodados.ibge.gov.br/api/v1/localidades/municipios/$id");
        return $response->getBody();
    }

    public function showEstados()
    {
        $result = DB::select('select * from estados');
        $result = Utf8_ansi(json_encode($result));
        return $result;
    }
}
