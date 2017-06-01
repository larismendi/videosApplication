<?php

namespace AppBundle\Controller;

use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ]);
    }

	public function loginAction(Request $request)
	{
		$helpers = $this->get("app.helpers");

		$jwt_auth = $this->get("app.jwt_auth");
		$em = $this->getDoctrine()->getManager();
		$jwt_auth->setManager($em);

		// Recibir json por post
		$json = $request->get("json", null);

		if ($json != null) {
			$params = json_decode($json);

			$email = isset($params->email) ? $params->email : null;
			$password = isset($params->password) ? $params->password : null;

			$emailConstraint = new Assert\Email();
			$emailConstraint->message = "This email is not valid!!";

			$validate_email = $this->get("validator")->validate($email, $emailConstraint);

			if (count($validate_email)==0 && $password != null) {
				return $helpers->json($jwt_auth->signup($email, $password));
			} else {
				echo "Data incorrect!!";
			}
		} else {
			echo "Send json with post !!";
		}
		die;
	}

	public function testAction(Request $request)
	{
		$helpers = $this->get("app.helpers");

		$em = $this->getDoctrine()->getManager();
		$users = $em->getRepository('BackendBundle:User')->findAll();

		return $helpers->json($users);
	}
}
