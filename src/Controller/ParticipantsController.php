<?php

namespace App\Controller;

use App\Entity\Participants;
use App\Entity\Sites;
use App\Form\ParticipantsType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_ADMIN')]
#[Route("/admin", name:'app_admin_')]
class ParticipantsController extends AbstractController
{

    #[Route('/participants', name:'participants', methods: ['GET', 'POST'])]
    public function participants(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
            /*
             * Récupère la liste des participants, et la liste des sites
             */
        $participants = $entityManager->getRepository(Participants::class)->findAll();
        $sites = $entityManager->getRepository(Sites::class)->findAll();
            /*
             *  Instanciation d'un participant pour via le formulaire
             */
        $newParticipant = new Participants();
            /*
             * Création du formulaire avec en paramètres :
             *      - Le modèle du formulaire (ParticipantsType::class)
             *      - L'objet $newParticipant qui sera alimenté par les données rentrées dans le formulaire
             *      - Tableau d'options qui permet d'envoyer la liste des sites (pour choisir le site parmi une liste déroulante)
             */
        $participantsForm = $this->createForm(ParticipantsType::class, $newParticipant, [
            'sites' => $sites,
        ]);
            /*
             * Validation des données selon les contraintes définies (required, length...)
             */
        $participantsForm = $participantsForm->handleRequest($request);

            /*-------------------- AJOUT D'UN PARTICIPANT --------------------*/
                /*
                 * Condition vérifiant si le formulaire a été soumis et validé
                 */
            if ($participantsForm->isSubmitted() && $participantsForm->isValid()) {
                    /*
                     * Récupère les données et les assigne à l'objet $newParticipant
                     */
                $newParticipant = $participantsForm->getData();
                    /*
                     * Récupère le mdp renseigné avec getPassword()
                     * Hash le mdp et le stock dans $hashedPassword
                     * Met à jour le mdp et l'assigne à l'objet $newParticipant avec setPassword()
                     */
                $hashedPassword = $passwordHasher->hashPassword($newParticipant, $newParticipant->getPassword());
                $newParticipant->setPassword($hashedPassword);
                    /*
                     * Persiste et enregistre les données dans la BDD
                     */
                $entityManager->persist($newParticipant);
                $entityManager->flush();
                    /*
                     * Message flash confirmant l'ajout du participant en BDD
                     */
                $this->addFlash('success', 'Le participant a été ajouté avec succès !');
                    /*
                     * Redirige vers la même page afin d'actualiser et d'éviter une double soumission du formulaire
                     */
                return $this->redirectToRoute('app_admin_participants');
            }

            /*---------- SUPPRESSION D'UN PARTICIPANT ----------*/
                /*
                 * Vérifie que la requête soit en POST et si elle contient un paramètre 'delete'
                 */
            if ($request->isMethod('POST') && $request->request->has('delete')) {
                    /*
                     * Fait référence à l'input name: "delete" dans la vue twig
                     * Récupère la valeur comprise (ici l'ID participant) et la stock dans $participantId
                     */
                $participantId = $request->request->get('delete');
                    /*
                    * Requête récupérant en BDD le participant selon l'ID (stocké dans $participantId)
                    */
                $participant = $entityManager->getRepository(Participants::class)->find($participantId);
                    /*
                     * Si le participant a été trouvé :
                     *      - Indique qu'il doit être supprimé
                     *      - Le supprime de la BDD avec flush()
                     */
                if ($participant) {
                    $entityManager->remove($participant);
                    $entityManager->flush();
                        /*
                         * Message flash notifiant la suppression (succès)
                         */
                    $this->addFlash('success', 'Le participant a été supprimé avec succès !');
                }
                else {
                        /*
                         * Sinon, message flash notifiant de l'échec de la suppression
                         */
                    $this->addFlash('danger', 'Le participant n\'existe pas !');
                }
                    /*
                     * Redirige vers la même page afin d'actualiser et d'éviter une double soumission du formulaire
                     */
                return $this->redirectToRoute('app_admin_participants');
            }
            /*
             * Retourne la vue Twig 'admin/participants.html.twig' avec en paramètres :
             *      - La liste des participants récupérés en BDD
             *      - L'objet représentant le formulaire (la méthode createView() génère une représentation visuelle en utilisant Twig)
             */
        return $this->render('admin/participants.html.twig', [
            'participants' => $participants,
            'participantsForm' => $participantsForm->createView(),
        ]);
    }

}