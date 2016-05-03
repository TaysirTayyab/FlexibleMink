<?php namespace Behat\FlexibleMink\PseudoInterface;

use Behat\Behat\Hook\Scope\AfterStepScope;
use Behat\Mink\Exception\UnsupportedDriverActionException;
use Behat\Testwork\Tester\Result\TestResult;
use Behat\FlexibleMink\PseudoInterface\MinkContextInterface;

/**
 * {@inheritdoc}
 */
trait ScreenShotContext
{
    // Depends
    use MinkContextInterface;
    use TestArtifactContextInterface;

    // Implements
    use ScreenShotContextInterface;

    /**
     * {@inheritdoc}
     *
     * @When /^(?:I )?take a screenshot(?: named "(?P<name>(?:[^"]|\\")*)")$/
     */
    public function takeScreenShot($name = 'screenshot')
    {
        file_put_contents(
            $this->getArtifactsDir() . '/' . $name . '-' . date('Ymd-His-') . uniqid('', true) . '.png',
            $this->getSession()->getDriver()->getScreenshot()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function takeScreenShotAfterFailedStep(AfterStepScope $scope)
    {
        if (TestResult::FAILED === $scope->getTestResult()->getResultCode()) {
            $name = str_replace(' ', '_', $scope->getFeature()->getTitle() . '-' . $scope->getStep()->getText());

            try {
                $this->takeScreenShot($name);
            } catch (UnsupportedDriverActionException $e) {
                // Silently ignore.
            }
        }
    }
}
