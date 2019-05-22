<?php

namespace App\Controller\Crawler;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class Student
 * @package App\Controller\Crawler
 */
class Student
{
    const URL_LOGIN = 'https://siga.cps.sp.gov.br/aluno/login.aspx?';
    const URL_HOME = 'https://siga.cps.sp.gov.br/aluno/home.aspx';
    const URL_FALTAS_PARCIAL = 'https://siga.cps.sp.gov.br/aluno/faltasparciais.aspx';
    const URL_NOTAS_PARCIAL = 'https://siga.cps.sp.gov.br/aluno/notasparciais.aspx';
    const URL_HISTORY = 'https://siga.cps.sp.gov.br/aluno/historicocompleto.aspx';
    const URL_SCHEDULE = 'https://siga.cps.sp.gov.br/aluno/horario.aspx';

    private $client;
    private $cookies;
    private $user;
    private $password;

    /**
     * Student constructor.
     * @param string $user
     * @param string $password
     */
    public function __construct(string $user, string $password)
    {
        $this->user = $user;
        $this->password = $password;
        $this->cookies = new CookieJar();
        $this->client = new Client(['cookies' => true]);

        $this->postLoginForm();
    }

    /**
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function postLoginForm()
    {
        $request = $this->client->request('POST', self::URL_LOGIN, [
            'form_params' => [
                'GXState' => '{"_EventName":"EENTER.","_EventGridId":"","_EventRowId":"","MPW0005_CMPPGM":"login_top.aspx","MPW0005GX_FocusControl":"","vREC_SIS_USUARIOID":"","GX_FocusControl":"vSIS_USUARIOID","GX_AJAX_KEY":"670337884953F2AD3069AEA655EFB534","AJAX_SECURITY_TOKEN":"B8EB9B12BEB5BDAA61133D16DC8F3FE1F2675B852B42470B5D381AF40ACBB31A","GX_CMP_OBJS":{"MPW0005":"login_top"},"sCallerURL":"","GX_RES_PROVIDER":"GXResourceProvider.aspx","GX_THEME":"GeneXusX","_MODE":"","Mode":"","IsModified":"1"}',
                'MAINFORM' => '',
                'vSIS_USUARIOID' => $this->user,
                'vSIS_USUARIOSENHA' => $this->password,
                'BTCONFIRMA' => 'Confirmar'
            ],
            'headers' => [
                'Referer' => self::URL_LOGIN
            ],
            'cookies' => $this->cookies
        ]);
        return true;
    }

    /**
     * @return JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getStudentInfo()
    {
        $data = $this->client->request('GET', self::URL_HOME, ['cookies' => $this->cookies])
            ->getBody()
            ->getContents();

        $parser = new SigaParser($data);

        return $parser->getStudentData();
    }

    /**
     * @return JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getHistory()
    {
        $data = $this->client->request('GET', self::URL_HISTORY, ['cookies' => $this->cookies])
            ->getBody()->getContents();

        $parser = new SigaParser($data);
        $result = $parser->getHistory();
        $response = new JsonResponse($result);

        return $response;
    }

    public function getSchedule()
    {
        $data = $this->client->request('GET', self::URL_SCHEDULE, ['cookies' => $this->cookies])
            ->getBody()->getContents();

        $parser = new SigaParser($data);
        $result = $parser->getSchedule();
//        dump($result->Grid1ContainerData);

        $response = new JsonResponse($result);

        return $response;
    }

}