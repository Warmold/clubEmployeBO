<?php


namespace App\Controller\Invitation;

use App\Form\Invitation\InvitationType;
use App\Manager\InvitationManager;
use App\Manager\UserManager;
use Knp\Component\Pager\Pagination\AbstractPagination;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class InvitationController
 */
class InvitationController extends AbstractController
{
    /**
     * @var InvitationManager
     */
    private $invitationManager;

    /**
     * InvitationController constructor.
     *
     * @param InvitationManager $invitationManager
     */
    public function __construct(InvitationManager $invitationManager)
    {
        $this->invitationManager = $invitationManager;
    }

    /**
     * @Route("/invitations", name="invitation_list")
     *
     * @Template("invitation/list.html.twig")
     *
     * @return array
     */
    public function list(): array
    {
        $invitations =$this->invitationManager->findAll();

        return [
            'invitations' => $invitations['items']
        ];
    }

    /**
     * @Route("/invitations/add", name="invitation_add")
     *
     * @Template("invitation/form.html.twig")
     *
     */
    public function add(Request $request)
    {
        $form = $this->createForm(InvitationType::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $response = $this->invitationManager->add($form->getData());

            if (Response::HTTP_UNPROCESSABLE_ENTITY === $this->invitationManager->getReturnCode()) {
                $violationsMapper->mapViolations($form, $response['violations']);
            } else {
                $this->addFlash('success', 'app.flash.success');

                return $this->redirectToRoute('invitation_list');
            }
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/invitations/{uuid}/confirm",
     *     name="invitation_confirm",
     *     methods={"POST"},
     * )
     *
     * @param String              $uuid
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function confirm(string $uuid): Response
    {
        $invitation = $this->invitationManager->confirm($uuid);

        if (Response::HTTP_NOT_FOUND === $this->invitationManager->getReturnCode()) {
            throw $this->createNotFoundException('Invitation not found');
        }

        return $this->redirectToRoute('invitation_list');
    }

    /**
     * @Route("/invitations/{uuid}/refuse",
     *     name="invitation_refuse",
     *     methods={"POST"},
     * )
     *
     * @param String              $uuid
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function refuse(string $uuid): Response
    {
        $invitation = $this->invitationManager->refuse($uuid);

        if (Response::HTTP_NOT_FOUND === $this->invitationManager->getReturnCode()) {
            throw $this->createNotFoundException('Invitation not found');
        }

        return $this->json([
            'message' => 'success',
        ]);
    }

    /**
     * @Route("/invitations/{uuid}/cancel",
     *     name="invitation_cancel",
     *     methods={"POST"},
     * )
     *
     * @param String              $uuid
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function cancel(string $uuid): Response
    {
        $invitation = $this->invitationManager->cancel($uuid);

        if (Response::HTTP_NOT_FOUND === $this->invitationManager->getReturnCode()) {
            throw $this->createNotFoundException('Invitation not found');
        }

        return $this->json([
            'message' => 'success',
        ]);
    }

    /**
     * @Route("/user/autocomplete", name="user_autocomplete")
     * @param Request $request
     * @return JsonResponse
     */
    public function autocomplete(Request $request, UserManager $userManager): JsonResponse
    {
        /** @var AbstractPagination $userManagerr */
        $users = $userManager->findAll();
        return new JsonResponse([
            'results' => array_map(static function ($item) {
                return [
                    'id' => $item['uuid'],
                    'text' => $item['email'],
                ];
            }, $users['items']),
        ]);
    }
}