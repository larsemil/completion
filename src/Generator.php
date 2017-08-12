<?php

namespace larsemil\completion;


// credit goes to CLIFramework
// https://github.com/c9s/CLIFramework/blob/master/src/Completion/BashGenerator.php

class generator
{

    protected $autocompletes;

    protected $cmdname;

    protected $bashfile;

    public function __construct($_cmdname = null, array $_autocompletes)
    {
        if($_cmdname) {
            $this->cmdname = $_cmdname;
            $this->autocompletes = $_autocompletes;

            $this->createBashCompletionScript()
                ->runInBash();
        }

    }

    public function createBashCompletionsScript()
    {
        $this->bashfile = file_get_contents('bash.template');
        $this->bashfile = str_replace('#CMDNAME#',$this->cmdname);

        $this->bashfile = str_replace('#AVAILABLEARGUMENTS#', $this->generateArgumentsString());

        $this->bashfile = '<< EOF'.PHP_EOL.$this->bashfile.PHP_EOL.'EOF';
        return $this;

    }

    public function runInBash(){
        shell_exec($this->bashfile);
    }


    public function generateArgumentsString(){
        return implode(" ", $this->autocompletes);
    }

}