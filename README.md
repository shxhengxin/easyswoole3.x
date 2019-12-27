### 安装yaconf
```$xslt

    git clone https://github.com/laruence/yaconf.git
    cd yacong  && phpize  && ./configure  && make && make install
    

    找到php.ini

    加上如下：
    extension=yaconf
    yaconf.directory=/home/vagrant/code/easyswoole/ini
    ps: /home/vagrant/code/easyswoole  指向根目录
        ini  指向存放配置文件的地方 
```