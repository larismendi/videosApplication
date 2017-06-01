<?php
/**
 * Created by PhpStorm.
 * User: larismendi
 * Date: 1/6/2017
 * Time: 5:56 PM
 */

namespace AppBundle\Controller;

use BackendBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller
{
	public function newAction(Request $request){
		$helpers = $this->get("app.helpers");

		$json = $request->get("json", null);
		$params = json_decode($json);
		$data = array();

		if($json != null){
			$createdAt = new \DateTime("now");
			$image = null;
			$roles = "user";

			$email = isset($params->email) ? $params->email : null;
			$name = (isset($params->name) && ctype_alpha($params->name)) ? $params->name : null;
			$surname = (isset($params->surname) && ctype_alpha($params->surname)) ? $params->surname : null;
			$password = isset($params->password) ? $params->password : null;

			$emailConstraint = new Assert\Email();
			$emailConstraint->message = "This email is not valid!!";
			$validate_email = $this->get("validator")->validate($email, $emailConstraint);

			if($email != null && count($validate_email) == 0 &&
				$password != null && $name != null && $surname != null){
				$user = new User();
				$user->setCreatedAt($createdAt);
				$user->setEmail($email);
				$user->setName($name);
				$user->setSurname($surname);
				$user->setPassword($password);
				$user->setImage($image);
				$user->setRoles($roles);

				$em = $this->getDoctrine()->getManager();
				$isset_user = $em->getRepository("BackendBundle:User")->findBy(
					array(
						"email" => $email
					)
				);

				if(count($isset_user) == 0){
					$em->persist($user);
					$em->flush();

					$data["status"] = 'success';
					$data["msg"] = 'New user created !!';
				}else{
					$data = array(
						"status" => "error",
						"code" => 400,
						"msg" => "User not created, duplicated!"
					);
				}
			}
		}else{
			$data = array(
				"status" => "error",
				"code" => 400,
				"msg" => "User not created"
			);
		}

		return $helpers->json($data);
	}
}