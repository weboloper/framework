<?php
/**
 * PhalconSlayer\Framework Helpers.
 *
 * @copyright 2015-2016 Daison Carino <daison12006013@gmail.com>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://docs.phalconslayer.com
 */

/**
 */
namespace Components\Clarity\Console;

use Symfony\Component\Console\Helper;
use Components\Clarity\Services\ServiceMagicMethods;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

/**
 * An abstract class that handles all slayer console.
 */
abstract class Brood extends Command
{
    use ServiceMagicMethods;

    /**
     * The command call name.
     *
     * @var string
     */
    protected $name;

    /**
     * The command description.
     *
     * @var string
     */
    protected $description;

    /**
     * The command help.
     *
     * @var string
     */
    protected $help;

    /**
     * The input interface instance.
     *
     * @var mixed
     */
    protected $input;

    /**
     * The output interface instance.
     *
     * @var mixed
     */
    protected $output;

    /**
     * The environment option.
     *
     * if this is marked as true (boolean), it will activate the --env option
     *
     * @var bool
     */
    protected $environment_option = true;

    /**
     * The timeout option.
     *
     * if this is marked as true (boolean), it will activate the --timeout option
     *
     * @var bool
     */
    protected $timeout_option = true;

    /**
     * A default constant timeout.
     */
    const TIMEOUT = 60;

    /**
     * An abstract function that will be called on every providers.
     *
     * @return mixed
     */
    abstract public function slash();

    /**
     * {@inheritdoc}
     *
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;

        $this->loadEnv();

        $this->loadTimeout();

        $this->slash();
    }

    /**
     * This loads the environment.
     *
     * @return \Components\Clarity\Console\Brood
     */
    protected function loadEnv()
    {
        $env = $this->getInput()->getOption('env');

        $used_env = '';
        $folder = '';

        if (config('environment')) {
            $folder = $used_env = config('environment');
        }

        if ($env !== null) {
            config(['old_environment' => config('environment')]);
            config(['environment' => $env]);
            $folder = $used_env = $env;
        }

        $folder_path = url_trimmer(config()->path->config.'/'.$folder);

        if (file_exists($folder_path) === false) {
            $this->error("Config Folder [$used_env] not found.");
        }

        config(
            iterate_require(folder_files($folder_path)),
            $merge = false
        );

        return $this;
    }

    /**
     * This loads the timeout.
     *
     * @return \Components\Clarity\Console\Brood
     */
    protected function loadTimeout()
    {
        $timeout = $this->getInput()->getOption('timeout');

        set_time_limit($timeout);

        return $this;
    }

    /**
     * Get the timeout option
     *
     * @return array
     */
    public static function timeoutOption()
    {
        return [
            'timeout',
            't',
            InputOption::VALUE_OPTIONAL,
            'Set timeout to bypass default execution time',
            static::TIMEOUT
        ];
    }

    /**
     * Get the environment option
     *
     * @return array
     */
    public static function environmentOption()
    {
        return [
            'env',
            'e',
            InputOption::VALUE_OPTIONAL,
            'The environment to be used',
            null
        ];
    }

    /**
     * Returns the input interface instance.
     *
     * @return mixed
     */
    protected function getInput()
    {
        return $this->input;
    }

    /**
     * Returns the output interface instance.
     *
     * @return mixed
     */
    protected function getOutput()
    {
        return $this->output;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName($this->name)
            ->setDescription($this->description);

        if ($this->help) {
            $this->setHelp($this->help);
        }

        if (! empty($this->arguments())) {
            foreach ($this->arguments() as $arg) {
                $this->addArgument(
                    isset($arg[0]) ? $arg[0] : null,
                    isset($arg[1]) ? $arg[1] : null,
                    isset($arg[2]) ? $arg[2] : null,
                    isset($arg[3]) ? $arg[3] : null
                );
            }
        }

        $options = [];

        if ($this->environment_option) {
            $options[] = static::environmentOption();
        }

        if ($this->timeout_option) {
            $options[] = static::timeoutOption();
        }

        $options = array_merge($this->options(), $options);

        if (! empty($options)) {
            foreach ($options as $opt) {
                $this->addOption(
                    isset($opt[0]) ? $opt[0] : null,
                    isset($opt[1]) ? $opt[1] : null,
                    isset($opt[2]) ? $opt[2] : null,
                    isset($opt[3]) ? $opt[3] : null,
                    isset($opt[4]) ? $opt[4] : null
                );
            }
        }

        return $this;
    }

    /**
     * This provides the arguments of this console.
     *
     * @return mixed
     */
    protected function arguments()
    {
        return [];
    }

    /**
     * This provides the options of this console.
     *
     * @return mixed
     */
    protected function options()
    {
        return [];
    }

    /**
     * This prints-out an info colored message.
     *
     * @param  string $message The message to be printed
     * @return void
     */
    public function info($message)
    {
        $this->output->writeln("<info>$message</info>");
    }

    /**
     * This prints-out a line colored message.
     *
     * @param  string $message The message to be printed
     * @return void
     */
    public function line($message)
    {
        $this->output->writeln($message);
    }

    /**
     * This prints-out a comment colored message.
     *
     * @param  string $message The message to be printed
     * @return void
     */
    public function comment($message)
    {
        $this->output->writeln("<comment>$message</comment>");
    }

    /**
     * To print a message block.
     *
     * @param  string $message Your message
     * @param  string $color   The color to set
     * @param  string $large   If the block is large all just small (default: true)
     * @return void
     */
    public function block($message, $color = 'bg=blue;fg=white', $large = true)
    {
        $formatter = $this->getHelperSet()->get('formatter');

        $this->output->writeln([
            '',
            $formatter->formatBlock($message, $color, $large),
            '',
        ]);
    }

    /**
     * This prints-out an error colored message.
     *
     * @param  string $message The message to be printed
     * @return void
     */
    public function error($message)
    {
        $this->output->writeln("<error>$message</error>");
    }

    /**
     * This handles exception and prints-out error colored message.
     *
     * @param  \Exception $e
     * @return void
     */
    public function exception($e)
    {
        $message = sprintf(
            '%s: %s (uncaught exception) at %s line %s',
            get_class($e),
            $e->getMessage(),
            $e->getFile(),
            $e->getLine()
        );

        $this->block($message, 'bg=red;fg=white');
    }

    /**
     * Ask something with a default value.
     *
     * @param  string                $message The message to be printed
     * @param  bool|string|int]mixed $default The default value
     * @return mixed
     */
    public function ask($message, $default = null)
    {
        $helper = $this->getHelper('question');

        $question = new Question($message, $default);

        return $helper->ask($this->input, $this->output, $question);
    }

    /**
     * To confirm that only works with [y/n].
     *
     * @param  string $message The message to be printed
     * @param  string $default The default value
     * @return mixed
     */
    public function confirm($message, $default = false)
    {
        $helper = $this->getHelper('question');

        $question = new ConfirmationQuestion($message, $default);

        return $helper->ask($this->input, $this->output, $question);
    }

    /**
     * This will create a table in the console.
     *
     * @return Helper\Table
     */
    public function table($headers = [], $rows = [])
    {
        $table = new Helper\Table($this->output);

        foreach ($rows as $idx => $row) {
            if (in_array($row, ['', null, '---']) === true) {
                $rows[$idx] = new Helper\TableSeparator();
            }
        }

        $table
            ->setHeaders($headers)
            ->setRows($rows);

        return $table;
    }

    /**
     * Call the composer's dumpautoload.
     *
     * @return void
     */
    public function callDumpAutoload()
    {
        CLI::bash([
            'composer dumpautoload',
        ]);
    }
}
