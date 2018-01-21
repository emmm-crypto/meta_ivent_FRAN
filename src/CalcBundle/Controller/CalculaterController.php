<?php

namespace CalcBundle\Controller;

use CalcBundle\Entity\Buyers;
use CalcBundle\Entity\Seller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CalculaterController extends Controller
{
    /**
     * @Route("/set_seller")
     *
     * @param Request $request
     * @return Response
     */
    public function setSellerAction(Request $request)
    {
        try {
            $jsonData = $request->get('data');

            $data = json_decode($jsonData, 1);

            $em = $this->getDoctrine()->getManager();
            $seller = new Seller();

            $seller->setFIO($data['FIO']);
            $seller->setCity($data['city']);
            $seller->setEmail($data['email']);
            $seller->setAdvertising($data['advertising']);
            $seller->setCosts($data['cost']);
            $seller->setDescription($data['description']);
            $seller->setIncomes($data['incomes']);
            $seller->setInvestments($data['investments']);
            $seller->setPaush($data['paush']);
            $seller->setRoyalty($data['royalty']);
            $seller->setTitle($data['title']);
            $seller->setYear($data['year']);
            $seller->setPhone($data['phone']);

            $em->persist($seller);
            $em->flush();

            return new Response(true);
        } catch (\Exception $e) {
            return new Response(false);
        }
    }

    /**
     * @Route("/setBuyer")
     *
     * @param Request $request
     * @return Response
     */
    public function setBuyerAction(Request $request){
        try {
            $jsonData = $request->get('data');

            $data = json_decode($jsonData, 1);

            $em = $this->getDoctrine()->getManager();
            $seller = $this->getDoctrine()
                ->getRepository('CalcBundle:Seller')
                ->findOneBy(array(
                    'Title' => $data['title'],
                    )
                );

            $buyers = new Buyers();

            $buyers->setPhone($data['phone']);
            $buyers->setEmail($data['email']);
            $buyers->setFIO($data['fio']);
            $buyers->addTitle($seller);

            $em->persist($buyers);
            $em->flush();

            return new Response(true);
        } catch (\Exception $e) {
            return new Response(false);
        }
    }

    public function getSellersActions(){
        try {
            $em = $this->getDoctrine()->getManager();
            $qb = $em->createQueryBuilder('s');

            $qb->select('s')
                ->from('Seller', 's');
            $sellers = $qb->getResult();

            return new JsonResponse($sellers);
        } catch (\Exception $e) {
            return new Response($e->getMessage());
        }
    }

    public function getBuyersActions(){
        try {
            $em = $this->getDoctrine()->getManager();
            $qb = $em->createQueryBuilder('b');

            $qb->select('b')
                ->from('Buyers', 'b');
            $sellers = $qb->getResult();

            return new JsonResponse($sellers);
        } catch (\Exception $e) {
            return new Response($e->getMessage());
        }
    }
}
