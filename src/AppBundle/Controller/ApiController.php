<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ApiController extends Controller
{
    /** @const images limit per page */
    const PAGE_LIMIT = 10;

    /**
     * Returns list of albums as JSON
     *
     * @Route("/albums", name="albums_list", condition="request.isXmlHttpRequest()")
     */
    public function listAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Album');
        $albums = $repository->findAll();
        $serializer = $this->get('app.serializer');
        return new JsonResponse($serializer->toJson($albums));
    }

    /**
     * Returns all images from the album by pagination as JSON
     *
     * @Route("/album/{albumId}", name="album_images", condition="request.isXmlHttpRequest()")
     */
    public function albumAction(Request $request, $albumId)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Album');
        $album = $repository->find($albumId);
        if (null==$album)
            throw $this->createNotFoundException();
        $serializer = $this->get('app.serializer');
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $album->getImages(),
            $request->query->getInt('page', 1),
            self::PAGE_LIMIT
        );
        return new JsonResponse($serializer->toList($pagination));
    }

    /**
     * Returns the image by encoded Id
     *
     * @Route("/image/{encodedImageId}", name="image")
     */
    public function imageAction(Request $request, $encodedImageId)
    {
        $encoder = $this->get('app.encoder');

        $imageId = $encoder->decodeId($encodedImageId);
        $repository = $this->getDoctrine()->getRepository('AppBundle:Image');
        $image = $repository->find($imageId);
        if (null==$image || !is_file($image->getPath()))
            throw $this->createNotFoundException();

        $response = new StreamedResponse();
        $response->headers->set("Content-Type", $image->getMime());
        $response->headers->set("Content-Length", filesize($image->getPath()));
        $response->headers->set("ETag", $image->getMd5());
        $response->headers->set("Last-Modified", gmdate("D, d M Y H:i:s", filemtime($image->getPath()))." GMT");
        $response->setCallback(function() use ($image) {
            echo $image->getContent();
        });
        return $response;
    }
}
