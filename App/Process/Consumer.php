<?php


namespace App\Process;


use EasySwoole\Component\Di;
use EasySwoole\Component\Process\AbstractProcess;
use EasySwoole\EasySwoole\Logger;
use EasySwoole\Pool\Manager;

class Consumer extends AbstractProcess
{
    private $isRun = false;

    protected function run($arg)
    {
        // TODO: Implement run() method.
        /*
         * 举例，消费redis中的队列数据
         * 定时500ms检测有没有任务，有的话就while死循环执行
         */
        $this->addTick(500,function (){
            if(!$this->isRun){
                $this->isRun = true;

                while (true){
                    try{
                        $task = Di::getInstance()->get("REDIS")->lPop('task_list');

                        if($task){
                            // do you task
                            var_dump($this->getProcessName().' task run check');
                            Logger::getInstance()->log($this->getProcessName()."====".$task);
                        }else{
                            break;
                        }
                    }catch (\Throwable $throwable){
                        break;
                    }
                }
                $this->isRun = false;
            }

        });
    }



}