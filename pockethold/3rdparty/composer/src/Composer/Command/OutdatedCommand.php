<?php











namespace Composer\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;




class OutdatedCommand extends ShowCommand
{
protected function configure()
{
$this
->setName('outdated')
->setDescription('Shows a list of installed packages that have updates available, including their latest version.')
->setDefinition(array(
new InputArgument('package', InputArgument::OPTIONAL, 'Package to inspect. Or a name including a wildcard (*) to filter lists of packages instead.'),
new InputOption('outdated', 'o', InputOption::VALUE_NONE, 'Show only packages that are outdated (this is the default, but present here for compat with `show`'),
new InputOption('all', 'a', InputOption::VALUE_NONE, 'Show all installed packages with their latest versions'),
new InputOption('direct', 'D', InputOption::VALUE_NONE, 'Shows only packages that are directly required by the root package'),
new InputOption('strict', null, InputOption::VALUE_NONE, 'Return a non-zero exit code when there are outdated packages'),
new InputOption('minor-only', 'm', InputOption::VALUE_NONE, 'Show only packages that have minor SemVer-compatible updates. Use with the --outdated option.'),
new InputOption('format', 'f', InputOption::VALUE_REQUIRED, 'Format of the output: text or json', 'text'),
))
->setHelp(
<<<EOT
The outdated command is just a proxy for `composer show -l`

The color coding (or signage if you have ANSI colors disabled) for dependency versions is as such:

- <info>green</info> (=): Dependency is in the latest version and is up to date.
- <comment>yellow</comment> (~): Dependency has a new version available that includes backwards
  compatibility breaks according to semver, so upgrade when you can but it
  may involve work.
- <highlight>red</highlight> (!): Dependency has a new version that is semver-compatible and you should upgrade it.


EOT
)
;
}

protected function execute(InputInterface $input, OutputInterface $output)
{
$args = array(
'show',
'--latest' => true,
);
if (!$input->getOption('all')) {
$args['--outdated'] = true;
}
if ($input->getOption('direct')) {
$args['--direct'] = true;
}
if ($input->getArgument('package')) {
$args['package'] = $input->getArgument('package');
}
if ($input->getOption('strict')) {
$args['--strict'] = true;
}
if ($input->getOption('minor-only')) {
$args['--minor-only'] = true;
}
$args['--format'] = $input->getOption('format');

$input = new ArrayInput($args);

return $this->getApplication()->run($input, $output);
}




public function isProxyCommand()
{
return true;
}
}
