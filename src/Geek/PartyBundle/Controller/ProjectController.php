<?php

namespace Geek\PartyBundle\Controller;

use Doctrine\ORM\EntityManager;
use Geek\PartyBundle\Exception\InvalidUploadedFile;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Geek\PartyBundle\Entity\Work;
use Geek\PartyBundle\Form\ProjectType;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Project (Work) controller.
 *
 * @Route("/project")
 */
class ProjectController extends Base\BaseController
{
    private function isAdmin()
    {
        return $this->get('security.context')->isGranted('ROLE_ADMIN');
    }

    public function checkRights(Work $entity)
    {
        if ((!$this->getUser() || $entity->getAuthor() !== $this->getUser()) &&
            !$this->isAdmin()
        ) {
            return $this->redirect($this->generateUrl('geek_index'));
        }

        return null;
    }

    public function update(Request $request, $id = null)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $workEntityRepository = $em->getRepository('GeekPartyBundle:Work');

        $response = [];

        /** @var $entity \Geek\PartyBundle\Entity\Work */
        if ($id) {
            $entity = $workEntityRepository->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Work entity.');
            }

            if (null !== ($redirect = $this->checkRights($entity))) {
                return $redirect;
            }

            $response['delete_form'] = $this->createDeleteForm($id);
        } else {
            $entity = new Work();
        }

        $editForm = $this->createForm(new ProjectType(), $entity);
        $editForm->submit($request);

        if ($editForm->isValid()) {

            if ($entity->getId()) {
                $workWithSameId = $workEntityRepository->find($entity->getId());
                if ($workWithSameId && $workWithSameId !== $entity) {
                    $this->addErrorMessage('Работа с таким идентификатором уже существует');

                    $parameters = $this->arrayResponse([
                        'entity' => $entity,
                        'edit_form' => $editForm->createView(),
                    ]);
                    return $this->render('GeekPartyBundle:Project:new.html.twig', $parameters);
                }
            }

            if (!$entity->getParty()) {
                $currentParty = $this->getCurrentParty();
                $entity->setParty($currentParty);
            }
            if (!$entity->getAuthor()) {
                $entity->setAuthor($this->getUser());
            }

            $em->persist($entity);
            $em->flush();

            $response = new RedirectResponse($this->generateUrl('geek_browse'), 302);

            try {
                $this->uploadFiles($editForm, $entity);
            } catch (InvalidUploadedFile $e) {
                $this->addErrorMessage($e->getMessage());
            }

            return $response;
        }

        $response['entity'] = $entity;
        $response['edit_form'] = $editForm->createView();
        return $this->arrayResponse($response);
    }

    /**
     * Lists all Work entities.
     *
     * @Route("/", name="project")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('GeekPartyBundle:Work')->findBy([
            'author' => $this->getUser()
        ]);

        return $this->arrayResponse(array(
            'works' => $entities,
        ));
    }

    /**
     * Finds and displays a Work entity.
     *
     * @Route("/{id}/show", name="project_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GeekPartyBundle:Work')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Work entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Work entity.
     *
     * @Route("/new", name="project_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Work();
        $form   = $this->createForm(new ProjectType(), $entity);

        return $this->arrayResponse(array(
            'entity' => $entity,
            'edit_form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new Work entity.
     *
     * @Route("/create", name="project_create")
     * @Method("POST")
     * @Template("GeekPartyBundle:Work:new.html.twig")
     */
    public function createAction(Request $request)
    {
        return $this->update($request);
    }

    /**
     * Displays a form to edit an existing Work entity.
     *
     * @Route("/{id}/edit", name="project_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var $entity Work */
        $entity = $em->getRepository('GeekPartyBundle:Work')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Work entity.');
        }

        $editForm = $this->createForm(new ProjectType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->arrayResponse(array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Work entity.
     *
     * @Route("/{id}/update", name="project_update")
     * @Method("POST")
     * @Template("GeekPartyBundle:Work:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        return $this->update($request, $id);
    }

    /**
     * Deletes a Work entity.
     *
     * @Route("/{id}/delete", name="project_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            /** @var \Geek\PartyBundle\Entity\Work $entity */
            $entity = $em->getRepository('GeekPartyBundle:Work')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Work entity.');
            }

            if (null !== ($redirect = $this->checkRights($entity))) {
                return $redirect;
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('geek_index'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm();
    }

    /**
     * @param UploadedFile $file
     * @param $entityId
     * @param $partyDir
     * @throws InvalidUploadedFile
     * @return array
     */
    private function uploadIcon(UploadedFile $file, $entityId, $partyDir)
    {
        if (strpos($file->getMimeType(), 'image/') !== 0) {
            throw new InvalidUploadedFile('Иконка должна быть картинкой PNG или JPG');
        }

        $filename = $entityId . '_icon.png';
        $path = $partyDir . '/' . $filename;
        $file->move($partyDir, $filename);
        $im = new \Imagick($path);
        $im->resizeimage(120, 110, \Imagick::FILTER_UNDEFINED, 1, true);
        $im->writeimage($path);
        return array($file, $path);
    }

    /**
     * @param UploadedFile $file
     * @param $entityId
     * @param $partyDir
     * @throws InvalidUploadedFile
     */
    private function uploadGame(UploadedFile $file, $entityId, $partyDir)
    {
        if ($file->getMimeType() != 'application/zip') {
            throw new InvalidUploadedFile('Архив должен быть в формате ZIP');
        }
        $dir = $partyDir . '/' . $entityId;
        $path = $dir . '/archive.zip';
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        $file->move($dir, 'archive.zip');
        $zip = new \ZipArchive();
        if ($zip->open($path)) {
            $li = $zip->locateName('index.html');
            if (false === $li) {
                unlink($path);
                throw new InvalidUploadedFile('Архив должен содержать файл index.html');
            }
            $zip->extractTo($dir);
        }
        unlink($path);
    }

    /**
     * @param $editForm
     * @param $entity
     */
    private function uploadFiles(Form $editForm, Work $entity)
    {
        $partyDir = $this->get('kernel')->getRootDir() . '/../public_html/works/' . $entity->getParty()->getId();
        if (!is_dir($partyDir)) {
            mkdir($partyDir, 0777, true);
        }

        if ($iconFile = $editForm['icon']->getData()) {
            $this->uploadIcon($iconFile, $entity->getId(), $partyDir);
        }
        if ($gameFile = $editForm['file']->getData()) {
            if ($entity->getParty()->isCurrent() || $this->isAdmin()) {
                $this->uploadGame($gameFile, $entity->getId(), $partyDir);
            } else {
                $message = "Событие " . $entity->getParty()->getName() . " еще не началось или уже закончилось.
                    Чтобы обновить работу, обратитесь к администрации сайта.";
                $this->addErrorMessage($message);
            }
        }
    }
}
