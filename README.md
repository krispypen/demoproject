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
python maintenance.py quick
apachectl restart
exit
```

# Basic project structure using Composer

Next up, basic project structure using Composer

```bash
cd /home/projects/demoproject/data/
sudo rm -Rf demoproject/
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

echo "$(curl -fsSL https://raw.github.com/gist/3423648/fullreload)" > fullreload
chmod a+x fullreload

```

# Use the apcClassLoader

Comment out line 11 and 12 in web/app.php and change "sf2" into a unique name

# Use AppCache when not behind a Varnish server:

change this:

```php
//$kernel = new AppCache($kernel);
```

into this:

```php
if (!isset($_SERVER['HTTP_SURROGATE_CAPABILITY']) || false === strpos($_SERVER['HTTP_SURROGATE_CAPABILITY'], 'ESI/1.0')) {
    $kernel = new AppCache($kernel);
}
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

parameters.yml (Don't forget to change the searchindexname, sentry.dsn and websitetitle param)

```yaml
    # KunstmaanSearchBundle
    searchport: 9200
    searchindexname: demoproject
    # KunstmaanSentryBundle
    sentry.dsn: https://XXXXXXXX:XXXXXXXX@app.getsentry.com/XXXX
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

add this to the framework config for easy switch to pdo sessions

```yaml
framework:
    #storage_id: session.storage.pdo ## disabled because you need to manually create the table after fullreload. fix could be creating an entity for this table. see symfony.com/doc/current/cookbook/configuration/pdo_session_storage.html
```

add this to the main config:
```yaml
parameters:
    #pdo.db_options:
    # db_table: session
    # db_id_col: session_id
    # db_data_col: session_value
    # db_time_col: session_time
    
stof_doctrine_extensions:
    default_locale: nl
    translation_fallback: true
    orm:
        default:
           loggable: true
           translatable: true
           sluggable: true

liip_imagine:
    cache_prefix: uploads/cache
    driver: imagick
    #cache: no_cache
    filter_sets:
        thumb_image_block_1:
            quality: 75
            filters:
                thumbnail: { size: [310, 229], mode: outbound }
        thumb_image_block_2:
            quality: 75
            filters:
                thumbnail: { size: [630, 229], mode: outbound }

liip_cache_control:
    rules:
        - { path: /admin, controls: { private: true}, vary: [Accept-Encoding] }
        - { path: ^/_internal, controls: {private: true, max_age: 0} }
        - { path: ^/(.+), controls: { public: true, max_age: 120, s_maxage: 240 }, vary: [Accept-Encoding,Cookie] }

services:
    twig.extension.text:
       class: Twig_Extensions_Extension_Text
       tags:
           - { name: twig.extension }

    monitor.check.deps_entries:
        class: Liip\MonitorExtraBundle\Check\DepsEntriesCheck
        arguments:
            - %kernel.root_dir%
        tags:
            - { name: monitor.check }

    monitor.check.symfony_version:
        class: Liip\MonitorExtraBundle\Check\SymfonyVersionCheck
        tags:
            - { name: monitor.check }

    #pdo:
    # class: PDO
    # arguments:
    # - "mysql:dbname=%database_name%"
    # - %database_user%
    # - %database_password%

    #session.storage.pdo:
    # class: Symfony\Component\HttpFoundation\SessionStorage\PdoSessionStorage
    # arguments: [@pdo, %session.storage.options%, %pdo.db_options%]

    kunstmaan_logging_introspection:
        class: Monolog\Processor\IntrospectionProcessor
        tags:
            - { name: monolog.processor }

    kunstmaan_logging_web:
        class: Symfony\Bridge\Monolog\Processor\WebProcessor
        tags:
            - { name: monolog.processor }

    kunstmaan_logging_formatter:
        class: Monolog\Formatter\LineFormatter
```

add these to twig

```yaml
    globals:
        websitetitle: %websitetitle%
        defaultlocale: %defaultlocale%
        requiredlocales: %requiredlocales%
        #titlecolor: "#000000"
        #titlebgcolor: "#F53111"
        #ga_code: %ga_code% ## don't forget to specify this parameter in parameters.yml
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

security.yml (Don't forget to change the firewall.main.remember_me.domain parameter)

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
