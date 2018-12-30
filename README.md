# LenovoMall
Lenovo Online Mall

## 目录结构说明（未作解释或未列出的目录不用管，开发时基本用不到）
```
/ 根目录
|--Dockerfile 方便使用Docker进行部署
|--run.sh 配合Dockerfile使用，一键部署脚本(Linux下)
|--TODO 所有的TODO写在这里面
|--联想商城APP产品需求设计文档.doc 设计文档
|--www web根目录，直接将这个文件夹放在Linux的/var/下覆盖即可
   |--databas.sql 数据库创建脚本
   |--application codeigniter应用程序目录
      |--cache
      |--config 程序的配置文件目录，里面存放了程序所有的配置\
         |--config.php 程序主要的配置
         |--database.php 数据库配置，需要填写数据库账户密码
         |--routes.php 路由配置，将url中的路由映射到控制器中
      |--controllers 程序的控制器，用于控制程序六级
      |--core
      |--helpers
      |--hooks
      |--language
      |--libraries
      |--logs
      |--models 程序用到的模型
      |--third_party
      |--views 视图，用于控制页面的显示，前端主要在这个目录下工作
   |--html 这个目录是网站的根目录，所以前端开发时应用的资源文件可以直接从网站根目录下应用
      |--.htaccess
      |--index.php
      |--robots.txt
      |--assets 资源文件
         |--captcha 验证码图片的临时目录
         |--css css文件存放目录
         |--fonts 字体文件目录
         |--img 图片目录
         |--js JavaScript文件目录
         |sounds 声音文件目录
   |--system
   ```
## 前端开发
   前端主要在views目录里面写前端的文件（html），在里面引用资源文件（如js或css）直接从网站根目录引用

   如：//assets/js/main.js或//assets/img/logo.svg

   将需要引用的js等资源文件应放在html/assets目录下
   
## 后端开发
   后端首先需要在models中建立各种模型，如User、Goods等，全部面向对象

   例如：

   /models/user_model.php
   ```php
   class User_model extends CI_Model {
       public function __construct(){
          $this->load->database(); // 加载数据库
       }
       
       public function GetName(){
           return "Srpopty";
       }
   }
   ```
   
   之后在controllers中编写逻辑控制

   例如：

   /controllers/user.php
   ```php
   defined('BASEPATH') OR exit('No direct script access allowed');
   class User extends CI_Controller {
        public function __construct() {
            parent::__construct();
            $this->load->model('user_model'); // 加载刚才写的user_model.php，之后可以通过$this->user_model调用方法
            $this->load->config('email'); // 加载配置中的email.php，之后可以通过$this->email调用方法
            $this->load->helper('string'); // 加载helper
            $this->load->library('email'); // 加载第三方库的email
            $this->load->library('session');
            $this->load->helper('email');
            $this->load->helper('url');
        }
   
        public function send_email($subject, $content, $target) { // 发送邮件
            $this->email->from('admin@srpopty.cn', 'admin');
            $this->email->to($target);
            $this->email->subject($subject);
            $this->email->message($content);
            if( $this->email->send())
                return true;
            else
                return false;
        }    
    }
    
   ```
   
## 路由
   在/config/routes.php中添加路由

   例如：

   ```php
   $route['hahahaha'] = 'user/GetName';
   ```
   代表当访问url为http://xxx.xxx.xxx.xxx/hahahaha时，将调用控制器user.php中GetName函数，该函数中的输入将作为返回页面

   更多例子：

   ```php
   $route['hahahaha/(:num)'] = 'user/GetName'; // 当访问url为http://xxx.xxx.xxx.xxx/hahahaha/加任意数字(如123,222)会调用user/GetName
   $route['hahahaha/heiheihei/(:any)'] = 'user/GetName'; // 当访问url为http://xxx.xxx.xxx.xxx/hahahaha/heiheihei/加任意字符(如aaa,123,a12)会调用user/GetName
   $route['default_controller'] = 'home/view'; // 表示默认页面即当访问url为http://xxx.xxx.xxx.xxx/时调用home控制器中的view函数
   ```
   
## 视图
   使用`this->load->view('/templates/header');`调用views中的视图，可以累加调用

   例如：
   ```php
   if ($this->is_logined() === false){ // templates等目录均在views中，如/views/templates/header.php
			$this->load->view('/templates/header');
			$this->load->view('/slide_bar/header');
			$this->load->view('/slide_bar/content_visitor.php');
			$this->load->view('/home/content');
			$this->load->view('/slide_bar/footer');
			$this->load->view('/templates/footer');
		}else if($this->is_admin() === false){
			$this->load->view('/templates/header');
			$this->load->view('/slide_bar/header');
			$this->load->view('/slide_bar/content_user.php');
			$this->load->view('/slide_bar/footer');
			$this->load->view('/templates/footer');
		}else {
			$this->load->view('/templates/header');
			$this->load->view('/slide_bar/header');
			$this->load->view('/slide_bar/content_admin.php');
			$this->load->view('/slide_bar/footer');
			$this->load->view('/templates/footer');
		}
   ```
   
## 获取数据
   使用$this->input获取提交的数据，详细使用方法看官方文档

## 部署
均在Linux下完成

1. 更新源
```
sudo apt update
sudo apt upgrade -y
sudo apt dist-upgrade -y
```

2. 安装Vim(可选)
```
sudo apt install vim -y
```

2. 安装git(可选)
```
sudo apt install git -y
```

3. 克隆仓库(可选)
```
sudo git clone https://github.com/OUC-Program-of-Lenovo/LenovoMall /var/www/
或者直接将源码解压放入/var/www
```

3. 移动源码
```
sudo mv /var/www/LenovoMall/www/* /var/www/
```

4. 安装依赖软件包
```
sudo apt install apache2
sudo apt install php7.0
sudo apt install php7.0-gd
sudo apt install php7.0-mysqli
sudo apt install libapache2-mod-php7.0
sudo apt install mysql-server
```

5. 配置 php 与 apache
```
sudo phpenmod gd
启动 rewrite 模块用于美化 URL
sudo a2enmod rewrite
```

6. 修改 apache2 配置文件
```
sudo vim /etc/apache2/apache2.conf
找到对应位置，将其修改为
<Directory /var/www/>
        SetEnv CI_ENV production
        Options FollowSymLinks
        AllowOverride ALL
        Require all granted
</Directory>

如果是开发环境，则将production替换为development
```


7. 创建可写目录用于保存验证码
```
sudo mkdir /var/www/html/assets/captcha(如果已存在则不用创建)
sudo chmod o+w /var/www/html/assets/captcha
```

8. 登录与创建数据库
```
mysql -u root -p
登陆后创建数据库
create database Lenovo;
```

9. 导入数据库
```
mysql -u root -p -D Lenovo < database.sql
```

10. 配置数据库
```
1. cd /var/www/application/config/
sudo cp /var/www/appalication/config/database.php.example /var/www/appalication/config/database.php

2. 修改数据库信息:
sudo vim /var/www/appalication/config/database.php
username
password
database = Lenovo
```

11. 配置发信邮箱(可选)
```
1. cp /var/www/appalication/config/email.php.example /var/www/appalication/config/email.php

2. 根据你的邮件服务提供商的配置说明进行配置
sudo vim /var/www/appalication/config/email.php
主要需要修改 :
smtp_host
smtp_port
smtp_user
smtp_pass

3.然后vim /var/www/application/controllers/User.php
搜索到admin@srpopty.cn
将其替换为自己邮箱
```

12. 重启服务
```
sudo service mysql restart
sudo service apache2 restart
```

13. 默认管理员登录账号密码
```
用户名: admin
密码: admin123456
```

13. 默认普通用户登录账号密码
```
用户名: test
密码: test123456
```


## 虚拟机中开发环境配置
由于此项目部署在Linux系统中，但是在Windows中开发很不方便调试，因此可以将开发目录共享到Linux虚拟机中
1. 开启虚拟机

2. 在菜单栏->虚拟机->设置->选项->共享文件夹中选择添加，添加开发目录，取名为www

3. 将开发目录添加进去后，在虚拟机中查看是否添加成功
```
ls /mnt/hgfs/
若出现www目录，则添加成功
```

4. 添加完成后，创建软连接到/var中
```
首先删除原来的www目录
sudo rm -rf /var/www/

创建软连接
sudo ln -s /mnt/hgfs/www /var/
```

之后在Windows中对开发目录下源码的修改都将直接共享到Linux中，方便调试