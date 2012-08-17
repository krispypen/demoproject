# Setting up the project using kDeploy

First we create the new project with kDeploy. If you don't use kDeploy skip this section.

```bash
sudo -i
cd /opt/kDeploy/tools
python newproject.py demoproject
```

and we configure it for Symfony usage

```bash
python applyskel.py demoproject symfony
```

At this time it installs v2.0.16 and we want to work with 2.1 so we delete the files installed.

```bash
rm -Rf /home/projects/demoproject/data/demoproject/*
```

Run fixperms and maintenance

```bash
python fixperms.py demoproject
python maintenance quick
apachectl restart
exit
```

# Basic project structure using Composer

Next up, basic project structure using Composer

```bash
cd /home/projects/demoproject/data/
rm -Rf demoproject/
curl -s http://getcomposer.org/installer | php
php composer.phar create-project symfony/framework-standard-edition ./demoproject
mv composer.phar ./demoproject/
cd demoproject
```

While Symfony 2.1 is not final yet, make sure you have the latest versions

```bash
git checkout master
php composer.phar update
```

# Cleaning out the Acme bundle

```bash
rm -Rf src/Acme/
grep -v "Acme" app/AppKernel.php > app/AppKernel.php.tmp
mv app/AppKernel.php.tmp app/AppKernel.php
grep "wdt\|profiler\|configurator\|main\|routing" app/config/routing_dev.yml > app/config/routing_dev.yml.tmp
mv app/config/routing_dev.yml.tmp app/config/routing_dev.yml
rm -Rf web/bundles/acmedemo
```

# Configure your application

Configure your application by surfing to http://computername/config.php and ake sure parameters.yml or .ini is not readable in git.

```bash
echo "app/config/parameters.yml" >> .gitignore
echo "$(curl -fsSL https://raw.github.com/gist/c1125c1f97c76dd6cf99/param)" > param
chmod a+x param
./param encode
```

# Add the project to git

```bash
echo ".idea" >> .gitignore
rm -Rf .git
git init
git add .
git commit -a -m "Symfony base install"
```

# Adding bundles

Add the following to your composer.json and run ```php composer.phar update``

```json
        "kunstmaan/admin-bundle": "2.0.x-dev",
        "kunstmaan/media-bundle": "2.0.x-dev",
        "kunstmaan/pagepart-bundle": "2.0.x-dev",
        "kunstmaan/media-pagepart-bundle": "2.0.x-dev",
        "kunstmaan/form-bundle": "2.0.x-dev",
        "kunstmaan/adminlist-bundle": "2.0.x-dev",
        "kunstmaan/adminnode-bundle": "2.0.x-dev",
        "kunstmaan/view-bundle": "2.0.x-dev",
        "kunstmaan/search-bundle": "2.0.x-dev",
        "kunstmaan/generator-bundle": "2.0.x-dev",
        "kunstmaan/sentry-bundle": "dev-master",
        "liip/monitor-bundle": "dev-master",
        "liip/monitor-extra-bundle": "dev-master",
        "liip/cache-control-bundle": "dev-master"
```

and the following to AppKernel.php

```php
            // KunstmaanAdminBundle
            new FOS\UserBundle\FOSUserBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
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
```

parameters.yml

```yaml
    # KunstmaanSearchBundle
    searchport: 9200
    searchindexname: demoproject
    # KunstmaanSentryBundle
    sentry.dsn: https://5f267019e884404c9ad6f600562ecae8:2ac17b2abef44446a92742e940002a0c@app.getsentry.com/2067
    # KunstmaanMediaBundle
    cdnpath: ""
    # KunstmaanViewBundle
    requiredlocales: "nl|fr|de|en"
    defaultlocale: "nl"
    # KunstmaanAdminBundle
    websitetitle: "Demoproject"
 ```

routing.yml

```yaml
# LiipMonitorBundle
_monitor:
    resource: "@LiipMonitorBundle/Resources/config/routing.yml"
    prefix: /monitor/health

# KunstmaanMediaBundle
_imagine:
    resource: .
    type:     imagine

KunstmaanMediaBundle:
    resource: "@KunstmaanMediaBundle/Resources/config/routing.yml"
    prefix:   /

# KunstmaanAdminBundle
KunstmaanAdminBundle:
    resource: "@KunstmaanAdminBundle/Resources/config/routing.yml"
    prefix:   /

# KunstmaanAdminNodeBundle
KunstmaanAdminNodeBundle:
    resource: "@KunstmaanAdminNodeBundle/Resources/config/routing.yml"
    prefix:   /

# KunstmaanPagePartBundle
KunstmaanPagePartBundle:
    resource: "@KunstmaanPagePartBundle/Resources/config/routing.yml"
    prefix:   /

# KunstmaanFormBundle
KunstmaanFormBundle:
    resource: "@KunstmaanFormBundle/Resources/config/routing.yml"
    prefix:   /

# KunstmaanViewBundle
KunstmaanViewBundle_slug:
    resource: "@KunstmaanViewBundle/Controller/SlugController.php"
    type:     annotation
    prefix:   /
```

config.yml

```yaml
imports:
    - { resource: @KunstmaanMediaBundle/Resources/config/config.yml }
    - { resource: @KunstmaanAdminBundle/Resources/config/config.yml }
    - { resource: @KunstmaanFormBundle/Resources/config/config.yml }
    - { resource: @KunstmaanSearchBundle/Resources/config/config.yml }
    - { resource: @KunstmaanAdminListBundle/Resources/config/config.yml }
```

uncomment the translator in framework

```yaml
framework:
    translator:      { fallback: %locale% }
```

add these to twig

```yaml
    globals:
        websitetitle: %websitetitle%
        defaultlocale: %defaultlocale%
        requiredlocales: %requiredlocales%
```

update the assetic bundle statement to include the admin bundle

```yaml
    bundles:        [ "KunstmaanAdminBundle" ]
```

and update the orm statement to look like

```yaml
    orm:
        auto_generate_proxy_classes: %kernel.debug%
        entity_managers:
            default:
                auto_mapping: true
                metadata_cache_driver: apc
                result_cache_driver: apc
                query_cache_driver: apc
                mappings:
                    gedmo_translatable:
                        type: annotation
                        prefix: Gedmo\Translatable\Entity
                        dir: "%kernel.root_dir%/../vendor/gedmo/doctrine-extensions/lib/Gedmo/Translatable/Entity"
                        alias: GedmoTranslatable # this one is optional and will default to the name set for the mapping
                        is_bundle: false
                    gedmo_translator:
                        type: annotation
                        prefix: Gedmo\Translator\Entity
                        dir: "%kernel.root_dir%/../vendor/gedmo/doctrine-extensions/lib/Gedmo/Translator/Entity"
                        alias: GedmoTranslator # this one is optional and will default to the name set for the mapping
                        is_bundle: false
                    gedmo_loggable:
                        type: annotation
                        prefix: Gedmo\Loggable\Entity
                        dir: "%kernel.root_dir%/../vendor/gedmo/doctrine-extensions/lib/Gedmo/Loggable/Entity"
                        alias: GedmoLoggable # this one is optional and will default to the name set for the mapping
                        is_bundle: false
                    gedmo_tree:
                        type: annotation
                        prefix: Gedmo\Tree\Entity
                        dir: "%kernel.root_dir%/../vendor/gedmo/doctrine-extensions/lib/Gedmo/Tree/Entity"
                        alias: GedmoTree # this one is optional and will default to the name set for the mapping
                        is_bundle: false
```

security.yml

```yaml
jms_security_extra:
    secure_all_services: false
    expressions: true

security:
    encoders:
        "FOS\UserBundle\Model\UserInterface": sha512

    role_hierarchy:
        ROLE_ADMIN:       ROLE_USER
        ROLE_SUPER_ADMIN: [ROLE_USER, ROLE_ADMIN, ROLE_ALLOWED_TO_SWITCH]
        ROLE_NEWS:        ROLE_USER

    providers:
        fos_userbundle:
                id: fos_user.user_manager

    firewalls:
        main:
            pattern: .*
            form_login:
                login_path: fos_user_security_login
                check_path: fos_user_security_check
                provider: fos_userbundle
            logout:
              path:   fos_user_security_logout
              target: KunstmaanAdminBundle_homepage
            anonymous:    true
            remember_me:
                key:      0f9a62b0231d78a86b4e4a2f87bc032e95f44ebf
                lifetime: 604800
                path:     /
                domain:   kunstmaan.be

        dev:
            pattern:  ^/(_(profiler|wdt)|css|images|js)/
            security: false


    access_control:
        - { path: ^/([^/]*)/login$, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/([^/]*)/resetting, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/([^/]*)/admin/settings/, role: ROLE_ADMIN }
        - { path: ^/([^/]*)/admin/settings, role: ROLE_ADMIN }
        - { path: ^/([^/]*)/admin/, role: ROLE_ADMIN }
        - { path: ^/([^/]*)/admin, role: ROLE_ADMIN }
```

Run schema update and load fixtures

