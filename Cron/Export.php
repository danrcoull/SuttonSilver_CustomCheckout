<?php
namespace SuttonSilver\CustomCheckout\Cron;
use \Psr\Log\LoggerInterface;
class Export {
    protected $logger;
    protected $export;

    public function __construct(LoggerInterface $logger,
                                \SuttonSilver\CustomCheckout\Model\Export\Export $export) {
        $this->logger = $logger;
        $this->export = $export;
    }

    /**
     * Write to system.log
     *
     * @return void
     */

    public function execute() {
        $this->logger->addInfo('Export Running....');
        $this->export->runExport();
        $this->logger->addInfo('Export Finished....');
    }

}