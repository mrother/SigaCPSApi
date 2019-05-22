<?php

namespace App\Controller\Crawler;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class SigaParser
 * @package App\Controller\Crawler
 */
class SigaParser
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * SigaParser constructor.
     * @param string $html
     */
    public function __construct(string $html = "")
    {
        $this->crawler = new Crawler($html);
    }

    /**
     * @return JsonResponse
     */
    public function getStudentData()
    {
        $data['name'] = trim($this->crawler->filterXPath('//*[@id="span_MPW0039vPRO_PESSOALNOME"]')->text());
        $data['ra'] = trim($this->crawler->filterXPath('//*[@id="span_MPW0039vACD_ALUNOCURSOREGISTROACADEMICOCURSO"]')->text());
        $data['pp'] = trim($this->crawler->filterXPath('//*[@id="span_MPW0039vACD_ALUNOCURSOINDICEPP"]')->text());
        $data['pr'] = trim($this->crawler->filterXPath('//*[@id="span_MPW0039vACD_ALUNOCURSOINDICEPR"]')->text());
        $data['max_pr'] = trim($this->crawler->filterXPath('//*[@id="span_MPW0039vMAX_ACD_ALUNOCURSOINDICEPR"]')->text());
        $data['photo'] = trim($this->crawler->filterXPath('//*[@id="MPW0039FOTO"]/img')->attr('src'));

        $response = new JsonResponse($data);
        return $response;
    }

    /**
     * @return mixed
     */
    public function getHistory()
    {
        $dados = $this->getValue('//input[@name="Grid1ContainerDataV"]');

        return json_decode($dados);
    }

    
    public function getSchedule()
    {
        $dados = [];
        $dados['disciplinas'] = json_decode($this->getValue('//input[@name="Grid1ContainerDataV"]'));

        $dados['seg'] = json_decode($this->getValue('//input[@name="Grid2ContainerDataV"]'));
        $dados['ter'] = json_decode($this->getValue('//input[@name="Grid3ContainerDataV"]'));
        $dados['qua'] = json_decode($this->getValue('//input[@name="Grid4ContainerDataV"]'));
        $dados['qui'] = json_decode($this->getValue('//input[@name="Grid5ContainerDataV"]'));
        $dados['sex'] = json_decode($this->getValue('//input[@name="Grid6ContainerDataV"]'));
        $dados['sab'] = json_decode($this->getValue('//input[@name="Grid7ContainerDataV"]'));

        return $dados;
    }


    /**
     * @param $xpath
     * @return string|null
     */
    private function getValue($xpath)
    {
        return $this->crawler->filterXPath($xpath)->attr('value');
    }

    /**
     * @return string
     */
    public function getHtml()
    {
        return $this->crawler->html();
    }

    /**
     * @return string|null
     */
    public function extractGXState()
    {
        return $this->getValue('//input[@name="GXState"]');
    }
}

/**
 * @param $Input
 * @return array|string
 */
function TrimArray($Input)
{
    if (!is_array($Input))
        return trim($Input);

    return array_map('TrimArray', $Input);
}
