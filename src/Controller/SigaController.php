<?php

namespace App\Controller;

use App\Controller\Crawler\Student;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class SigaController
 * @package App\Controller
 */
class SigaController extends AbstractController
{
    private $student;

    public function __construct()
    {
        $this->student = new Student('xxx', 'yyy');
    }

    public function index()
    {
        return $this->student->getStudentInfo();
    }

    public function history()
    {
        return $this->student->getHistory();
    }

    public function schedule()
    {
        $dados = $this->student->getSchedule();

        return $dados;
    }
}
