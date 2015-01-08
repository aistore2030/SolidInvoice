<?php
/**
 * This file is part of CSBill package.
 *
 * (c) 2013-2014 Pierre du Plessis <info@customscripts.co.za>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace CSBill\InstallBundle\Process\Step;

use CSBill\InstallBundle\Form\Step\LicenseAgreementForm;
use CSBill\InstallBundle\Installer\Step\LicenseAgreement;
use Sylius\Bundle\FlowBundle\Process\Context\ProcessContextInterface;
use Sylius\Bundle\FlowBundle\Process\Step\ControllerStep;

class SystemRequirementsStep extends ControllerStep
{
    /**
     * @param ProcessContextInterface $context
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function displayAction(ProcessContextInterface $context)
    {
        $requirements = $this->getSystemRequirements();

        return $this->render(
            'CSBillInstallBundle:Flow:system_check.html.twig',
            array(
                'requirements' => $requirements,
            )
        );
    }

    /**
     * @param ProcessContextInterface $context
     *
     * @return \Sylius\Bundle\FlowBundle\Process\Step\ActionResult|\Symfony\Component\HttpFoundation\Response
     */
    public function forwardAction(ProcessContextInterface $context)
    {
        $requirements = $this->getSystemRequirements();

        if (0 !== count($requirements->getFailedRequirements())) {
            return $this->redirectToRoute('_install_flow');
        }

        return $this->complete();
    }

    /**
     * @return \AppRequirements
     */
    private function getSystemRequirements()
    {
        $rootDir = $this->get('kernel')->getRootDir();

        require_once $rootDir . DIRECTORY_SEPARATOR . 'AppRequirements.php';

        return new \AppRequirements();
    }
}