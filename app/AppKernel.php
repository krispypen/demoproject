<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new JMS\AopBundle\JMSAopBundle(),
            new JMS\DiExtraBundle\JMSDiExtraBundle($this),
            new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),

            // KunstmaanAdminBundle
            new FOS\UserBundle\FOSUserBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new Kunstmaan\AdminBundle\KunstmaanAdminBundle(),
            // KunstmaanMediaBundle
            new Liip\ImagineBundle\LiipImagineBundle(),
            new Knp\Bundle\GaufretteBundle\KnpGaufretteBundle(),
            new Kunstmaan\MediaBundle\KunstmaanMediaBundle(),
            // KunstmaanPagePartBundle
            new Kunstmaan\PagePartBundle\KunstmaanPagePartBundle(),
            // KunstmaanMediaPagePartBundle
            new Kunstmaan\MediaPagePartBundle\KunstmaanMediaPagePartBundle(),
            // KunstmaanFormBundle
            new Kunstmaan\FormBundle\KunstmaanFormBundle(),
            // KunstmaanAdminListBundle
            new Kunstmaan\AdminListBundle\KunstmaanAdminListBundle(),
            // KunstmaanAdminNodeBundle
            new Kunstmaan\AdminNodeBundle\KunstmaanAdminNodeBundle(),
            // KunstmaanViewBundle
            new Kunstmaan\ViewBundle\KunstmaanViewBundle(),
            // KunstmaanSearchBundle
            new FOQ\ElasticaBundle\FOQElasticaBundle(),
            new Kunstmaan\SearchBundle\KunstmaanSearchBundle(),
            // KunstmaanGeneratorBundle
            new Kunstmaan\GeneratorBundle\KunstmaanGeneratorBundle(),
            // KunstmaanSentryBundle
            new Kunstmaan\SentryBundle\KunstmaanSentryBundle(),
            // LiipMonitorBundle & LiipMonitorExtraBundle
            new Liip\MonitorBundle\LiipMonitorBundle(),
            new Liip\MonitorExtraBundle\LiipMonitorExtraBundle(),
            // LiipCacheControlBundle
            new Liip\CacheControlBundle\LiipCacheControlBundle(),

        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
