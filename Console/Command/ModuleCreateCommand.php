<?php

declare(strict_types=1);

namespace DawidoFed\ModuleCreator\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ModuleCreateCommand extends Command
{
    /** @var string */
    private const NAME = 'name';

    protected function configure(): void
    {
        $this->setName('module:create');
        $this->setDescription('Create simple structure of new module in /app/code.');
        $this->addOption(
            self::NAME,
            null,
            InputOption::VALUE_REQUIRED,
            'Name'
        );

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output): bool
    {
        if ($name = $input->getOption(self::NAME)) {
            $name = explode('_', $name);
            if (count($name) <> 2) {
                $output->writeln('<comment>Nieprawidłowa nazwa nowego modułu. Nazwa powinna składać się z dwóch części rozdzielonych znakiem podkreślenia "_", np. Producent_NowyModul');
            } else {
                $manufacturer = $name[0];
                $newModule = $name[1];
                $info = $this->createModule($manufacturer, $newModule);
                foreach ($info as $k => $v) {
                    $output->writeln('<info>' . $v . '/' . self::NAME . ' created</info>');
                }
            }

        } else {
            $output->writeln('<comment>Nie podano nazwy modułu</comment>');
        }

        return true;
    }

    private function createModule(string $manufacturer, string $newModuleName)
    {
        exec('pwd', $output, $result_code);
        $pwd = $output[0];

        // create catalog
        $command = "mkdir -p " . $pwd . "/app/code/" . $manufacturer . "/" . $newModuleName . "/etc";
        exec($command, $output, $result_code);

        $registrationFile = $pwd . "/app/code/" . $manufacturer . "/" . $newModuleName . "/registration.php";
        $moduleFile = $pwd . "/app/code/" . $manufacturer . "/" . $newModuleName . "/etc/module.xml";

        // create registration.php
        exec("cat << EOF > " . $registrationFile . "
<?php

use Magento\Framework\Component\ComponentRegistrar;

ComponentRegistrar::register(
    ComponentRegistrar::MODULE,
    '" . $manufacturer . '_' . $newModuleName . "',
    __DIR__
);
EOF");

        // create module.xml
        exec("cat << EOF > " . $moduleFile . "
<?xml version=\"1.0\"?>
<config xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"
        xsi:noNamespaceSchemaLocation=\"urn:magento:framework:Module/etc/module.xsd\">
    <module name=\"" . $manufacturer . '_' . $newModuleName . "\"/>
</config>
EOF");

        return $output;
    }
}
