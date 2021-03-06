<?php


namespace EasySwoole\DocSystem\DocLib;


use EasySwoole\EasySwoole\Command\CommandInterface;
use EasySwoole\EasySwoole\Command\Utility;
use EasySwoole\Utility\File;

class DocCommand implements CommandInterface
{
    protected $root;
    function __construct(string $projectRoot)
    {
        $this->root = $projectRoot;
    }

    public function commandName(): string
    {
        return 'doc';
    }

    public function exec(array $args): ?string
    {
        if(isset($args[0])){
            if($args[0] == 'extra' && isset($args[1])){
                $lang = $args[1];
                $files = File::scanDirectory(__DIR__.'/Resource/Markdown');
                foreach ($files['files'] as $file){
                    $info = pathinfo($file);
                    Utility::releaseResource($file, $this->root ."/{$lang}/". $info['basename']);
                }
                if(file_exists($this->root.'/Static')){
                    return 'Static Dir is exits,you can recover it in manual ';
                }else{
                    $files = File::scanDirectory(__DIR__.'/Resource/Static');
                    foreach ($files['files'] as $file){
                        $new = str_replace(__DIR__."/Resource/",'',$file);
                        Utility::releaseResource($file, $this->root ."/{$new}");
                    }
                }
                return "{$lang} 语言目录创建成功";
            }
        }
        return $this->help($args);
    }

    public function help(array $args): ?string
    {
        return "php easyswoole doc extra LANGUAGE 创建语言目录";
    }

}