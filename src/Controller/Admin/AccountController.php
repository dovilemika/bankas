<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller\Admin;

use App\Entity\Account;
use App\Entity\Transfer;
use App\Form\TransferType;
use App\Repository\AccountRepository;
use App\Repository\TransferRepository;
use App\Voter\AccountVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller used to manage account contents in the backend.
 *
 * @Route("/accounts")
 * @IsGranted("ROLE_ADMIN")
 */
class AccountController extends AbstractController
{
    /**
     * @var TransferRepository
     */
    private $transferRepository;

    /**
     * AccountController constructor.
     */
    public function __construct(TransferRepository $transferRepository)
    {
        $this->transferRepository = $transferRepository;
    }

    /**
     * Lists all Account entities.
     *
     * @Route("/", methods="GET", name="accounts_index")
     */
    public function index(AccountRepository $posts): Response
    {
        $accounts = $posts->findBy(['user' => $this->getUser()]);

        return $this->render('admin/account/index.html.twig', ['accounts' => $accounts]);
    }

    /**
     * Creates a new Transfer entity.
     *
     * @Route("/transfer/new", methods="GET|POST", name="transfer_new")
     */
    public function new(Request $request): Response
    {
        $accountId = $request->query->get('id');
        $transfer = new Transfer();
        $transfer->setSender($this->getUser()->getAccount($accountId));
        $transfer->setStatus("Created");
        $transfer->setDate(new \DateTime('now'));

        $form = $this->createForm(TransferType::class, $transfer)
            ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            /** @var Account $senderAccount */
            $senderAccount = $transfer->getSender();
            /** @var Account $receiverAccount */
            $receiverAccount = $transfer->getReceiver();

            if ($transfer->getAmount() <= $senderAccount->getBalance()) {
                $senderAccount->setBalance($senderAccount->getBalance() - $transfer->getAmount());
                $receiverAccount->setBalance($receiverAccount->getBalance() + $transfer->getAmount());

                $em->persist($transfer);
                $em->persist($senderAccount);
                $em->persist($receiverAccount);
                $em->flush();

                $this->addFlash('success', 'Transfer created successfully');
            } else {
                $this->addFlash('warning', 'Could not create transfer: Amount entered is larger than current balance.');
            }


            return $this->redirectToRoute('accounts_index');
        }

        return $this->render('admin/account/new.html.twig', [
            'transfer' => $transfer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a Account entity.
     *
     * @Route("/{id<\d+>}", methods="GET", name="admin_post_show")
     */
    public function show(Account $account): Response
    {
        $sentTransfers = $this->transferRepository->findBy(['sender' => $account]);
        $receivedTransfers = $this->transferRepository->findBy(['receiver' => $account]);

        $allTransfers = array_merge($sentTransfers, $receivedTransfers);

        // security check for if user is account holder
        $this->denyAccessUnlessGranted(AccountVoter::SHOW, $account, 'Accounts can only be shown to their holders.');

        return $this->render('admin/account/show.html.twig', [
            'account' => $account,
            'transfers' => $allTransfers
        ]);
    }

    /**
     * Deletes a Transfer entity.
     *
     * @Route("/{id}/delete", methods="POST", name="transfer_delete")
     */
    public function delete(Request $request, Transfer $transfer): Response
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('accounts_index');
        }

        $em = $this->getDoctrine()->getManager();

        /** @var Account $senderAccount */
        $senderAccount = $transfer->getSender();
        /** @var Account $receiverAccount */
        $receiverAccount = $transfer->getReceiver();

        $senderAccount->setBalance($senderAccount->getBalance() + $transfer->getAmount());
        $receiverAccount->setBalance($receiverAccount->getBalance() - $transfer->getAmount());

        $em->persist($senderAccount);
        $em->persist($receiverAccount);
        $em->remove($transfer);
        $em->flush();

        $this->addFlash('success', 'Transfer cancelled successfully.');

        return $this->redirectToRoute('accounts_index');
    }
}
