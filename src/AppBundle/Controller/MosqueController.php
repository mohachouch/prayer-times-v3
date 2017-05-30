<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Mosque;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class MosqueController extends Controller {

    /**
     * @Route("/mosque/{slug}/{_locale}", name="mosque_deprected", requirements={"_locale"= "en|fr|ar"})
     * @ParamConverter("mosque", options={"mapping": {"slug": "slug"}})
     */
    public function mosqueDeprectedAction(Request $request, Mosque $mosque) {

        return $this->forward("AppBundle:Mosque:mosque", ["mosque" => $mosque]);
    }

    /**
     * @Route("/{slug}/{_locale}", name="mosque", requirements={"_locale"= "en|fr|ar"}, defaults={"_local"="fr"})
     * @ParamConverter("mosque", options={"mapping": {"slug": "slug"}})
     */
    public function mosqueAction(Request $request, Mosque $mosque) {

        return $this->render('mosque/mosque.html.twig', [
                    'lang' => $request->getLocale(),
                    'mosque' => $mosque,
                    'version' => $this->getParameter('version'),
                    "site" => $this->get("translator")->trans("prayer_mobile_site", [
                        "%site%" => $this->generateUrl("mosque", ["slug" => $mosque->getSlug()], UrlGenerator::ABSOLUTE_URL)
                    ]),
                    "supportTel" => $this->getParameter("supportTel"),
                    "supportEmail" => $this->getParameter("supportEmail"),
                    'config' => json_encode($mosque->getConfiguration()->getFormatedConfig())
        ]);
    }

    /**
     * @Route("/{slug}/has-been-updated/{lastUpdatedDate}")
     * @ParamConverter("mosque", options={"mapping": {"slug": "slug"}})
     */
    public function hasUpdatedAjaxAction(Request $request, Mosque $mosque, $lastUpdatedDate) {
        $hasBeenUpdated = $this->get("app.prayer_times_service")->mosqueHasBeenUpdated($mosque, $lastUpdatedDate);
        $response = [
            "hasBeenUpdated" => $hasBeenUpdated,
            "lastUpdatedDate" => $mosque->getUpdated(),
        ];
        return new JsonResponse($response, 200);
    }

    /**
     * @Route("/dua-slider/{width}")
     */
    public function duaSliderAction(Request $request, $width) {
        if ((int) $width > 1800) {
            return $this->render('partial/dua-slider-one-screen.html.twig');
        }
        return $this->render('partial/dua-slider.html.twig');
    }

}
