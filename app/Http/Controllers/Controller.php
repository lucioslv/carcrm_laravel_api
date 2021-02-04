<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function success($msg = 'Sucesso.', $time = 1200)
    {
        return response()->json(['status' => 200, 'success' => $msg, 'time' => $time], 200);
    }

    public function error($msg = 'Erro.', $time = 1200)
    {
        return response()->json(['status' => 400, 'error' => $msg, 'time' => $time], 400);
    }

    public function validateUrl($string)
    {
        $Format = array();
        $Format['a'] = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜüÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿRr"!@#$%&*()_-+={[}]/?;:.,\\\'´<>°ºª';
        $Format['b'] = 'aaaaaaaceeeeiiiidnoooooouuuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr                                  ';
        $Data = strtr(utf8_decode($string), utf8_decode($Format['a']), $Format['b']);
        $Data = strip_tags(trim($Data));
        $Data = str_replace(' ', '-', $Data);
        $Data = str_replace(array('-----', '----', '---', '--'), '-', $Data);
        return strtolower(utf8_encode($Data));
    }
}
