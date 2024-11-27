<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\DanfeResource;
use App\Models\Danfex;
use Illuminate\Http\Request;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

use Dompdf\Dompdf;
use Dompdf\Options;
use NFePHP\DA\NFe\Danfe;


class DanfeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //return DanfeResource::collection(Danfex::with('user')->get());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //faz a validação dos dados pois todos sao necessarios
        $validator = Validator::make($request->all(), [
            'inserido_por' => 'required',
            'chave' => 'required|max:44',
            'content_xml' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Erro de validação', 422, [], $validator->errors());
        }

        //Cria no bd apos validar
        $created = Danfex::create($validator->validated());

        //se criou  retorna uma instancia dos dados cadastrados
        if($created) {
            //load('user') faz com que o usuario seja carregado junto com a Danfe e importe o
            //relacionamento entre as tabelas
            return $this->successResponse('Danfe created', 200, 
            new DanfeResource($created->load('user')));
        } 

        return $this->errorResponse('Danfe created', 400, [], $validator->errors());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $chave){
        return new DanfeResource(Danfex::where('chave', $chave)->first());

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getNfeXml()
    {
       
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->getAccessToken(),
            'Accept' => 'application/xml',
        ])->get('https://gateway.apiserpro.serpro.gov.br/consulta-nfe-df/api/v1/nfe/29240627672847000179550010004564231005198019');

        if ($response->status() == 401) {
            $this->updateAccessToken();
            return $this->getNfe();
        }
        
        //Retorna o xml da nfe
        if ($response->successful()) {
            return $response;
        }

        return response()->json(['error' => 'Failed to fetch data'], $response->status());

    }

    /**
     * Get a temporary access token.
     */
    public function updateAccessToken()
    {
        $consumer_key= Env::get('VITE_SERPRO_CONSUMER_KEY');
        $consumer_secret = Env::get('VITE_SERPRO_CONSUMER_SECRET');
        $credentials = base64_encode("$consumer_key:$consumer_secret");        

        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . $credentials,
        ])->asForm()->post('https://gateway.apiserpro.serpro.gov.br/token', [
            'grant_type' => 'client_credentials',
        ]);

        return $_SESSION['access_token'] = $response->json()['access_token'];

        if ($response->successful()) {
            return $response;
        }

        return response()->json(['error' => 'Failed to fetch data'], $response->status());
        
    }

    /**
     * Get a temporary access token.
     */
    public function getAccessToken() {

        if(!isset($_SESSION['access_token'])) {
            $this->updateAccessToken();
        }

        return $_SESSION['access_token'];
    }

    //Gerar pdf
    public function getNfePdf() {
        $xml = $this->getNfeXml();
        $xmlBody = $xml->body();

        // Verificar se $xmlBody é uma string
        if (is_string($xmlBody)) {
            try {

                $danfe = new Danfe($xmlBody,
                'P',             // Orientação da página (P para retrato)
                'A4',            // Tamanho do papel (A4)
                '',              // Caminho para o logotipo (opcional)
                'I',             // Destino do PDF (I para inline)
                '/public/pdf',              // Diretório para salvar o PDF (se destino for F)
                '',              // Fonte do DANFE (opcional)
                1 );
                $pdf = $danfe->render();
                //return $pdf;
               
         
                file_put_contents('danfe.pdf', $pdf);

            } catch (\Exception $e) {
                return response()->json(['error' => 'Invalid XML format'], 400);
            }
            
        } else {
            return response()->json(['error' => 'Invalid XML format'], 400);
        }

        
    }


 
    
}
