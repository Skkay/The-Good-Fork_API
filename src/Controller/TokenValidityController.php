<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;

class TokenValidityController extends AbstractController
{
    /**
     * @Route("/api/token_validity", name="token_validity", methods={"GET"})
     */
    public function index(Request $request, JWTEncoderInterface $jwt): Response
    {
        $encodedToken = $request->query->get("token");

        try {
            $decodedToken = $jwt->decode($encodedToken);
            $expireIn = ($decodedToken['exp'] - \time());
            return $this->json(['valid' => true, 'expireIn' => $expireIn]);
        }
        catch (\Exception $e) {
            return $this->json(['valid' => false, 'reason' => $e->getReason()]);
        }   
    }
}
