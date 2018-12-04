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
   /controllers/
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
