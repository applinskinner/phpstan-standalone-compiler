<?php declare(strict_types = 1);

namespace PHPStan\Compiler\Console;

use PHPStan\Compiler\Filesystem\Filesystem;
use PHPStan\Compiler\Process\ProcessFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class CompileCommand extends Command
{

	/** @var Filesystem */
	private $filesystem;

	/** @var ProcessFactory */
	private $processFactory;

	/** @var string */
	private $dataDir;

	/** @var string */
	private $buildDir;

	public function __construct(
		Filesystem $filesystem,
		ProcessFactory $processFactory,
		string $dataDir,
		string $buildDir
	)
	{
		parent::__construct();
		$this->filesystem = $filesystem;
		$this->processFactory = $processFactory;
		$this->dataDir = $dataDir;
		$this->buildDir = $buildDir;
	}

	protected function configure(): void
	{
		$this->setName('phpstan:compile')
			->setDescription('Compile PHAR')
			->addArgument('version', InputArgument::OPTIONAL, 'Version (tag/commit) to compile', 'master')
			->addArgument('repository', InputArgument::OPTIONAL, 'Repository to compile', 'https://github.com/phpstan/phpstan.git');
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		if ($this->filesystem->exists($this->buildDir)) {
			$this->filesystem->remove($this->buildDir);
		}
		$this->filesystem->mkdir($this->buildDir);

		$this->processFactory->create(sprintf('git clone %s .', \escapeshellarg($input->getArgument('repository'))), $this->buildDir, $output);
		$this->processFactory->create(sprintf('git checkout --force %s', \escapeshellarg($input->getArgument('version'))), $this->buildDir, $output);
		$this->processFactory->create('composer require --no-update dg/composer-cleaner:^2.0', $this->buildDir, $output);
		$this->processFactory->create('composer update --no-dev --classmap-authoritative', $this->buildDir, $output);

		$this->processFactory->create('php box.phar compile', $this->dataDir, $output);

		return 0;
	}

}