<?php

namespace larsemil\completion;


// credit goes to CLIFramework
// https://github.com/c9s/CLIFramework/blob/master/src/Completion/BashGenerator.php

class CompletionGenerator
{

    protected $autocompletes;

    protected $cmdname;

    protected $bashfile;

    public function __construct($_cmdname = null, array $_autocompletes)
    {
        if($_cmdname) {
            $this->cmdname = $_cmdname;
            $this->autocompletes = $_autocompletes;

            $this->createBashCompletionsScript()
                ->runInBash();
        }

    }

    public function createBashCompletionsScript()
    {

        $this->bashfile = file_get_contents(__DIR__.'/bash.template');
        $this->bashfile = str_replace('#CMDNAME#',$this->cmdname, $this->bashfile);

        $this->bashfile = str_replace('#AVAILABLEARGUMENTS#', $this->generateArgumentsString(), $this->bashfile);

        $this->bashfile = '<< EOF'.PHP_EOL.$this->bashfile.PHP_EOL.'EOF';
        return $this;

    }

    public function runInBash(){


        //echo shell_exec('bash --noprofile --norc');
        //echo shell_exec('   echo $0');
        //echo shell_exec('whoami');
        //echo $this->bashfile;
        $f = fopen('dump.bash','w');
        fwrite($f, $this->bashfile);
        fclose($f);
        shell_exec('bash '.$this->bashfile);
    }


    public function generateArgumentsString(){
        return implode(" ", $this->autocompletes);
    }

}