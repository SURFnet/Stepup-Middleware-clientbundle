# Step-up Middleware Client Bundle
[![Build Status](https://travis-ci.org/SURFnet/Stepup-Middleware-clientbundle.svg)](https://travis-ci.org/SURFnet/Stepup-Middleware-clientbundle) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/SURFnet/Stepup-Middleware-clientbundle/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/SURFnet/Stepup-Middleware-clientbundle/?branch=develop) [![SensioLabs Insight](https://insight.sensiolabs.com/projects/3fef2be8-1351-4fbb-b6e8-00296c1d8d36/mini.png)](https://insight.sensiolabs.com/projects/3fef2be8-1351-4fbb-b6e8-00296c1d8d36)

A Symfony2 bundle to consume the Step-up Middleware API.

## Installation

 * Add the package to your Composer file
    ```sh
    composer require surfnet/stepup-middleware-client-bundle:~1.0
    ```

 * Add the bundle to your kernel in `app/AppKernel.php`
    ```php
    public function registerBundles()
    {
        // ...
        $bundles[] = new Surfnet\StepupMiddlewareClientBundle\SurfnetStepupMiddlewareClientBundle;
    }
    ```

## Usage

*TBD*
