<?php
namespace AppBundle\Services;

use Doctrine\ORM\EntityManager;
use Firebase\JWT\JWT;

class JwtAuth {
	private $manager;

	/**
	 * @return mixed
	 */
	public function getManager()
	{
		return $this->manager;
	}

	/**
	 * @param mixed $manager
	 */
	public function setManager($manager)
	{
		$this->manager = $manager;
	}


	/**
	 * @return mixed
	 */
	public function signup($email, $password, $gatHash = NULL)
	{
		$key = "secret-key";
		$user = $this->manager->getRepository('BackendBundle:User')->findOneBy(
			array(
				"email" => $email,
				"password" => $password
			)
		);

		var_dump($user);die;

		$signup = false;
		if(is_object($user)){
			$signup = true;
		}

		if($signup == true){
			echo "Login success !!";
		}else{
			return array("status" => "error", "data" => "Login failed !!");
		}
		return $this->manager;
	}
}