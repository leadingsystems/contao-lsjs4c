<?php


namespace LeadingSystems\LSJS4CBundle\EventSubscriber;


use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\CoreBundle\Routing\ScopeMatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;

class LsjsInsertionSubscriber implements EventSubscriberInterface
{
    private ScopeMatcher $scopeMatcher;
    private ContaoFramework $framework;
    private string $webDir;
    private string $projectDir;

    public function __construct(ContaoFramework $framework, ScopeMatcher $scopeMatcher, string $webDir, string $projectDir)
    {
        $this->framework = $framework;
        $this->scopeMatcher = $scopeMatcher;
        $this->webDir = $webDir;
        $this->projectDir = $projectDir;
    }

    public function onKernelControllerArguments(ControllerArgumentsEvent $event)
    {
        if ($this->scopeMatcher->isBackendMainRequest($event)) {
            require_once($this->projectDir . '/assets/lsjs/core/appBinder/binderController.php');

            $arr_config = [
                'pathForRenderedFiles' => $this->projectDir . '/assets/js',
                'includeApp' => 'no',
                'includeAppModules' => 'no',
                'debug' => '1',
                'no-cache' => '1',
                'no-minifier' => '1',
            ];

            $binderController = new \lsjs_binderController($arr_config);

            $GLOBALS['TL_JAVASCRIPT'][] = str_replace($this->projectDir, '', $binderController->getPathToRenderedFile());
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            ControllerArgumentsEvent::class => 'onKernelControllerArguments',
        ];
    }
}