<?php
/**
 * kipelovets <kipelovets@mail.ru>
 */

namespace Geek\PartyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
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
}