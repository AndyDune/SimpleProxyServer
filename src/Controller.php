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

use GuzzleHttp\Client;

class Controller
{
    public function actionBadSecret()
    {
        header("HTTP/1.0 404 Not Found");
        echo json_encode(['status' => 'bad_secret']);
    }

    public function actionProxy()
    {
        header("Cache-Control: no-cache");
        header("Pragma: no-cache");
        //header("HTTP/1.0 404 Not Found");

        $request = new \AndyDune\ArrayContainer\ArrayContainer($_REQUEST);

        if (!$request['url']) {
            header("HTTP/1.0 404 Not Found");
            echo json_encode(['status' => 'no_url']);
            return;
        }

        $client = new Client([
            'timeout' => 25,
            'allow_redirects' => [
                'max' => 4,        // allow at most 10 redirects.
                'strict' => false,      // use "strict" RFC compliant redirects.
                'referer' => false,      // add a Referer header
                'protocols' => ['https', 'http'],
                'track_redirects' => true
            ],
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.8.1.7) Gecko/20070914 Firefox/2.0.0.7)',
            ]
        ]);

        try {
            $res = $client->request('GET', $request['url']);
            if (!$res) {
                header("HTTP/1.0 404 Not Found");
                echo json_encode(['status' => 'no_url']);
                return;
            }
        } catch (\Exception $e) {
            header("HTTP/1.0 404 Not Found");
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            return;
        }

        $code = $res->getStatusCode();
        header("HTTP/1.0 " . $code);
        $headers = $res->getHeaders();

        foreach ($headers as $name => $values) {
            foreach ($values as $value) {
                header(sprintf('%s: %s', $name, $value), false);
            }
        }

        $body = $res->getBody();
        echo $body->getContents();

    }
}