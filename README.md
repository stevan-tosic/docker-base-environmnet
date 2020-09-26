## Folders structure
  * `api/` - Symfony4 application - API
  * `client/` - React.js frontend application
       
## Project setup
  * [Installation instuctions (dev)](api/docs/installation-instuctions-development.md)
  
## Application Health Check
  * Application has a series of check to validate that host machine has all dependencies and configuration set up
  * To run this check use the following command (if you are using docker run it inside the php container) `php bin/console monitor:health`
  * For more info about the tool look at the official documentation for [LiipMonitorBundle](https://github.com/liip/LiipMonitorBundle)

#### Code architecture
  * Class naming concise and intuitive. Duplicate names are ok. Differentiation is done by namespaces. 
  * Slim Classes - One public method per class. Private methods are ok.
  * For the new API endpoints, use actions instead of controllers. Examples of [AbstractAction](api/src/Utils/AbstractAction.php) implementation is located in [Meeting component](api/src/Meetings/Application/Action/MeetingForm/CreateMeetingAction.php). Eventually, actions will replace controllers.
  * Code is structured in folders that mimic GUI section that uses them. See [example](api/docs/code-architecture-example.md).
  * Use Repository Interface instead of injecting Doctrine implementations. Refactoring of existing implementations is in progress.
  * Repositories should contain only basic methods (findById, findBy, save). For more complex queries use QueryObjects.

#### Routes  
  * Routes structured by Laravel REST convention - see [here](https://laravel.com/docs/5.7/controllers#resource-controllers) - table under `Actions Handled By Resource Controller`
  * Routes are structured to follow the resoure relations. For example to get all comments one message for one thread, the route would be /thread/{thread_id}/message/{message_id}/comments
  * Route parameters that do not uniquely define the resource should not be in route path, they shoud be in query params. For example, filters and pagination

#### Security
  * [PSR-9 Security Advisories](https://github.com/php-fig/fig-standards/blob/master/proposed/security-disclosure-publication.md)
  * [Composer security checks](https://security.sensiolabs.org/check)

#### Standards
  * [Symfony coding standards](http://symfony.com/doc/master/contributing/code/standards.html)

#### PHP static analysis tools used for reporting and pre-commit checks
  * [phpstan (level 7)](https://github.com/phpstan/phpstan)
  * [psalm](https://github.com/vimeo/psalm)
  * [phpmd (LSEG CC)](https://github.com/nikic/PHP-Parser)
  * [phpcs (Symfony standards)](https://github.com/squizlabs/PHP_CodeSniffer)
  * [phpcpd](https://github.com/sebastianbergmann/phpcpd)
  * [phpparser](https://github.com/nikic/PHP-Parser)
  * [phpqa](http://symfony.com/doc/master/contributing/code/standards.html)
  * [phplock](https://github.com/sebastianbergmann/phploc)
  * [pdepend](https://github.com/pdepend/pdepend)
  * [PhpMetrics](https://github.com/phpmetrics/PhpMetrics)
  * [phplock](https://github.com/sebastianbergmann/phploc)

## Server configuration
  * [nginx client](api/docs/configuration/default.conf)
  * [nginx api](api/docs/configuration/api.conf)
  * [php.ini](api/docs/configuration/php.ini)
