<?php

namespace App\Controller;

use App\Entity\Link;
use App\Form\LinkType;
use App\Repository\LinkRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class LinkController extends AbstractController
{
    /**
     * @Route("/list", name="link_index", methods={"GET"})
     */
    public function index(LinkRepository $linkRepository): Response
    {
        return $this->render('link/index.html.twig', [
            'links' => $linkRepository->findAll(),
        ]);
    }

    /**
     * @Route("/", name="link_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $link = new Link();
        $form = $this->createForm(LinkType::class, $link);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            do {
              $shortLink = substr(md5(uniqid(rand(), TRUE)), 0, random_int(5, 9));
            } while($entityManager->getRepository(Link::class)->findOneBy(['short_link' => $shortLink]));

            $link->setShortLink($shortLink);
            $entityManager->persist($link);
            $entityManager->flush();

            return $this->redirectToRoute('link_index');
        }

        return $this->render('link/new.html.twig', [
            'link' => $link,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/view/{short_link}", name="link_show", methods={"GET"})
     */
    public function show(Link $link): Response
    {
        return $this->render('link/show.html.twig', [
            'link' => $link,
        ]);
    }

    /**
     * @Route("/edit/{short_link}", name="link_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Link $link): Response
    {
        $form = $this->createForm(LinkType::class, $link);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('link_index');
        }

        return $this->render('link/edit.html.twig', [
            'link' => $link,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete_form/{short_link}", name="delete_form", methods={"GET","POST"})
     */
    public function deleteForm(Request $request, Link $link): Response
    {
      $form = $this->createForm(LinkType::class, $link);
      $form->handleRequest($request);

      return $this->render('link/_delete_form.html.twig', [
        'link' => $link,
        'form' => $form->createView(),
      ]);
    }

    /**
     * @Route("/delete/{short_link}", name="link_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Link $link): Response
    {
        if ($this->isCsrfTokenValid('delete'.$link->getShortLink(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($link);
            $entityManager->flush();
        }

        return $this->redirectToRoute('link_index');
    }

    /**
     * @Route("/{short_link}", name="link_redirect", methods={"GET"})
     */
    public function redirectTo(Link $link): Response
    {
      return $this->redirect($link->getLink());
    }
}
