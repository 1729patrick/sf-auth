<?php

namespace App\Controller;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class DefaultController extends Controller
{
	/**
	 * @Route("/", name="default")
	 */
	public function index() {
		return $this->render('default/index.html.twig', [
			'controller_name' => 'DefaultController',
		]);
	}

	/**
	 * @Route("/admin", name="admin")
	 *
	 * @Template("default/index.html.twig")
	 */
	public function admin(){

		$texto = "Esse usuário não é administrador.";

		if ($this->isGranted('ROLE_ADMIN')){
			$texto = "Esse usuário é Administrador.";
		}

		return [
			'texto' => $texto
		];
	}


	/**
	 * @Route("/admin/dashboard", name="dashboard")
	 *
	 * @Template("default/dashboard.html.twig")
	 */
	public function dashboard(){
		return [];
	}

	/**
	 * @Route("/admin/relatorios", name="relatorios")
	 *
	 * @Template("default/relatorios.html.twig")
	 */
	public function relatorios(){
		return [];
	}

	/**
	 * @Route("/admin/login", name="login")
	 * @Template("/default/login.html.twig")
	 */
	public function login(Request $request, AuthenticationUtils $authUtils) {

		$error = $authUtils->getLastAuthenticationError();

		$lasUsername = $authUtils->getLastUsername();

		return [
			'error' => $error,
			'last_username' => @$lasUsername
		];
	}

	/**
	 * @param Request $request
	 *
	 * @Route("/insert")
	 */
	public function insert(Request $request){

		$em = $this->getDoctrine()->getManager();
		$user = new User();
		$user->setUsername("Patrick");
		$user->setEmail("patrick@flexpro.com");
		$user->setRoles("ROLE_USER");

		$encoder = $this->get('security.password_encoder');
		$pass = $encoder->encodePassword($user, "aaa");
		$user->setPassword($pass);
		$em->persist($user);

		$user = new User();
		$user->setUsername("admin");
		$user->setEmail("admin@flexpro.com");
		$user->setRoles("ROLE_ADMIN");

		$encoder = $this->get('security.password_encoder');
		$pass = $encoder->encodePassword($user, "123");
		$user->setPassword($pass);
		$em->persist($user);

		$em->flush();

		return new Response("<h1>Inserido com sucesso!</h1>");
	}
}
