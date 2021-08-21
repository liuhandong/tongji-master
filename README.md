# tongji-master

1. 下载
https://master.dl.sourceforge.net/project/xampp/XAMPP%20Windows/7.2.29/xampp-windows-x64-7.2.29-0-VC15.zip?viasf=1

2. 解压到盘根目录(目录名字固定是xampp)
D:\>cd xampp

D:\xampp>tree
卷 Windows 的文件夹 PATH 列表
卷序列号为 8227-989D
D:.
├─anonymous
│  └─incoming
├─apache
│  ├─bin
│  │  └─iconv
│  ├─conf
│  │  ├─extra
│  │  ├─original
│  │  │  └─extra
│  │  ├─ssl.crt
│  │  ├─ssl.csr
│  │  └─ssl.key
│  ├─error
│  │  └─include
│  ├─icons
│  │  └─small
│  ├─include
│  ├─lib
│  ├─logs
│  ├─manual
│  └─modules
├─cgi-bin
├─contrib
├─FileZillaFTP
……
3. 解压tongji-master-0107到xampp下
重命名tongji-master

D:\xampp>cd ./tongji-master

D:\xampp\tongji-master>tree
卷 Windows 的文件夹 PATH 列表
卷序列号为 8227-989D
D:.
├─addons
│  ├─alisms
│  │  ├─controller
│  │  ├─library
│  │  └─view
│  │      └─index
│  ├─captcha
│  │  ├─controller
│  │  └─library
│  │      ├─font
│  │      └─gif
│  ├─crontab
│  │  ├─application
│  │  │  ├─admin
│  │  │  │  ├─controller
│  │  │  │  │  └─general
│  │  │  │  ├─lang
│  │  │  │  │  └─zh-cn
│  │  │  │  │      └─general
│  │  │  │  └─view
│  │  │  │      └─general
│  │  │  │          └─crontab
│  │  │  └─common
│  │  │      └─model
│  │  ├─controller
│  │  └─public
│  │      └─assets
│  │          └─js
│  │              └─backend
│  │                  └─general
……

4. 修改xampp\apache\conf下httpd.conf
    252行改成
    DocumentRoot "/xampp/tongji-master-0107/public"
    <Directory "/xampp/tongji-master-0107/public">

   修改D:\xampp\tongji-master\application下database.php密码为123456
   30行
   'password'        => Env::get('database.password', '123456'),
   
   修改D:\xampp\tongji-master\application\admin\view\index\login.html
   96行 删除disabled="disabled"
   <button type="submit" class="btn btn-success btn-lg btn-block" >{:__('Sign in')}</button>

5. 运行xampp\下xampp-control.exe
    看看apache和mysql是否可以start。

6. 运行xampp\下xampp_start.exe
    XAMPP now starts as a console application.

    Instead of pressing Control-C in this console window, please use xampp_stop.exe
    to stop XAMPP, because it lets XAMPP end any current transactions and cleanup
    gracefully.

    2021-06-27 21:52:17 0 [Note] Using unique option prefix 'key_buffer' is error-prone and can break in the future. Please use the full name 'key_buffer_size' instead.
    2021-06-27 21:52:17 0 [Note] mysql\bin\mysqld.exe (mysqld 10.4.11-MariaDB) starting as process 11688 ...
    
    显示上面信息
    
7. 进入目录
   cd D:\xampp\mysql\bin
   执行sql
   mysql -u root -p
   #设置密码
   MariaDB [(none)]> set password for root@localhost = password('123456');
   #创建DB和打开code数据库
   MariaDB [(none)]> create database code;
   MariaDB [(none)]> use code;
   #导入code.sql数据
   MariaDB [(none)]> source D:\%path\code.sql

8. 访问http://127.0.0.1/
    就可以看到登录画面。

