<?php
/**
 * ----------------------------------------------
 * | Author: Andrey Ryzhov (Dune) <info@rznw.ru> |
 * | Site: www.rznw.ru                           |
 * | Phone: +7 (4912) 51-10-23                   |
 * | Date: 21.03.2018                            |
 * -----------------------------------------------
 *
 */


namespace AndyDune\SimpleProxyServer;


class Controller
{
    public function actionBadSecret()
    {
        echo json_encode(['status' => 'bad_secret']);
    }

    public function actionProxy()
    {
        echo json_encode(['status' => 'good']);
    }
}