<?php

namespace App\Controller;

use App\Entity\Player;
use App\Entity\Sport;
use App\Entity\Team;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class PlayerController extends AbstractController {


    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getPlayers() {

        $repo = $this->getDoctrine()->getRepository('App:Player');

        $players = $repo->findAll();

        return $this->render('getAllPlayers.html.twig', [
            'players' => $players
        ]);

    }

	public function getTeams() {

		$repo = $this->getDoctrine()->getRepository('App:Team');

		$teams = $repo->findAll();

		return $this->render('list_team.html.twig', [
			'teams' => $teams
		]);

	}


	public function addTeam(Request $request){

		$team = new Team();

		$form = $this->createFormBuilder($team)
			->add('name', TextType::class)
			->add('players', EntityType::class, [
				'class' => Player::class,
				'expanded' => true,
				'multiple' => true,
				'query_builder' => function (EntityRepository $er) {
					return $er ->createQueryBuilder('p')
						->where('p.team_id != :value')
						->setParameter('value', null);
				}
				])
			->add('save', SubmitType::class, [
				'label' => 'Créer Team'
			])
			->getForm();

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();

			$formData = $form->getData();

			$team->setName($formData->getName());

			$em->persist($team);
			$em->flush();

			return $this->redirectToRoute('list_team');

		}

		return $this->render('team/addTeam.html.twig', [
			'form' => $form->createView(),
		]);

    }

	public function deleteTeam($id)
	{
		$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('App:Team');

		$teamToDelete = $repo->find($id);
		$em->remove($teamToDelete);
		$em->flush();

		return $this->redirectToRoute('list_team');
	}


    public function addPlayerToTeam(){

        $em = $this->getDoctrine()->getManager();
        $repoPlayer = $em->getRepository('App:Player');
        $repoTeam = $em->getRepository('App:Team');

        $player = $repoPlayer->find(1);
        $team = $repoTeam->find(1);

        $player->setTeamId($team);

        $em->persist($player);
        $em->flush();

        return new \Symfony\Component\HttpFoundation\Response('OK');
    }

    public function addPlayerToTeamTwo($idPlayer, $idTeam){

        $em = $this->getDoctrine()->getManager();
        $repoPlayer = $em->getRepository('App:Player');
        $repoTeam = $em->getRepository('App:Team');

        $player = $repoPlayer->find($idPlayer);
        $team = $repoTeam->find($idTeam);

        $player->setTeamId($team);

        $em->persist($player);
        $em->flush();

        return new \Symfony\Component\HttpFoundation\Response('OK');
    }

	public function addPlayer(Request $request)
	{
		$player = new Player();

		$form = $this->createFormBuilder($player)
			->add('firstname', TextType::class)
			->add('lastname', TextType::class)
			->add('age', IntegerType::class)
			->add('sport', EntityType::class, [
				'class' => Sport::class,
				'choice_label' => 'name',
			])
			->add('teamId', EntityType::class, [
				'class' => Team::class,
				'choice_label' => 'name',
			])
			->add('save', SubmitType::class, [
				'label' => 'Créer Joueur'
			])
			->getForm();

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();

			$formData = $form->getData();

			$player->setFirstname($formData->getFirstName());
			$player->setLastname($formData->getLastName());
			$player->setAge($formData->getAge());
			$player->setSport($formData->getSport());
			$player->setTeamId($formData->getTeamId());

			$em->persist($player);
			$em->flush();

			return $this->redirectToRoute('player');

		}

		return $this->render('player/addPlayer.html.twig', [
			'form' => $form->createView(),
		]);
	}

	public function deletePlayer($id)
	{
		$em = $this->getDoctrine()->getManager();
		$repo = $em->getRepository('App:Player');

		$playerToDelete = $repo->find($id);
		$em->remove($playerToDelete);
		$em->flush();

		return $this->redirectToRoute('player');
	}

}










