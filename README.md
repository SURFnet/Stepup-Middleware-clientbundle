# Step-up Middleware Client Bundle
[![Build Status](https://travis-ci.org/SURFnet/Stepup-Middleware-clientbundle.svg)](https://travis-ci.org/SURFnet/Stepup-Middleware-clientbundle) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/SURFnet/Stepup-Middleware-clientbundle/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/SURFnet/Stepup-Middleware-clientbundle/?branch=develop) [![SensioLabs Insight](https://insight.sensiolabs.com/projects/TODO/mini.png)](https://insight.sensiolabs.com/projects/TODO)

A Symfony2 bundle to consume the Step-up Middleware API.

## Installation

 * Add the package to your Composer file
    ```sh
    composer require surfnet/stepup-middleware-client-bundle
    ```

 * Add the bundle to your kernel in `app/AppKernel.php`
    ```php
    public function registerBundles()
    {
        // ...
        $bundles[] = new Surfnet\StepupMiddlewareClientBundle\SurfnetStepupMiddlewareClientBundle;
    }
    ```

## Configuration

```yaml
surfnet_stepup_middleware_client:
    authorisation:
        username: john
        password: doe
    url:
        command_api: http://middleware.tld/command
```

## Usage

```php
# In the context of a Symfony2 controller action
$command = new \Surfnet\StepupMiddlewareClientBundle\Identity\Command\CreateIdentityCommand();
$command->uuid = (string) \Rhumsaa\Uuid\Uuid::uuid4();
$command->nameId = (string) \Rhumsaa\Uuid\Uuid::uuid4();

/** @var \Surfnet\StepupMiddlewareClientBundle\Service\CommandService $service */
$service = $this->get('surfnet_stepup_middleware_client.service.command');
$result = $service->execute($command);
```
