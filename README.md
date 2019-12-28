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

### 配置nginx代理
```$xslt
server {
    root /data/wwwroot/chat;
    server_name chat.cc;
    location / {
        proxy_http_version 1.1;
        proxy_set_header Connection "keep-alive";
        proxy_set_header X-Real-IP $remote_addr;
        if (!-f $request_filename) {
             proxy_pass http://192.168.10.10:9501;
        }
    }
}
```