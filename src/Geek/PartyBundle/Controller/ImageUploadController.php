<?php
/**
 * kipelovets <kipelovets@mail.ru>
 */

namespace Geek\PartyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class ImageUploadController extends Controller
{
    /**
     * @Route("/upload")
     * @Method("POST")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function actionUploadImage(Request $request)
    {
        $partyDir = $this->get('kernel')->getRootDir() . '/../public_html/images/';
        if ($request->files->count() > 0) {
            /** @var UploadedFile $file */
            $file = current($request->files->getIterator());
            $name = md5_file($file->getPathname()) . '.' . $file->getClientOriginalExtension();
            $file->move($partyDir, $name);
            return new Response('{"image":{"url":"/images/' . $name . '"}}');
        }

        return new Response('{"error":{"message":"Fail"}}');
    }

    /**
     * @Route("/filemanager/{file}.php", requirements={"file":"(ajax_calls|dialog|execute|force_download|upload)"})
     *
     * @param Request $request
     * @return Response
     */
    public function actionFilemanager(Request $request)
    {
        $file = $request->attributes->get('file');
        ob_start();
        require_once($this->get('kernel')->getRootDir() . "/../public_html/filemanager/{$file}.php");
        $content = ob_get_contents();
        ob_end_clean();
        return new Response($content);
    }

    /**
     * @Route("/filemanager/{path}", requirements={"path"=".+\.(css|gif|ico|jar|jpg|js|map|php|png|swf)"})
     *
     * @param Request $request
     * @return Response
     */
    public function actionFilemanagerStatic(Request $request)
    {
        $path = $request->attributes->get('path');
        $fullPath = $this->get('kernel')->getRootDir() . "/../public_html/filemanager/{$path}";
        return new BinaryFileResponse($fullPath);
    }
}