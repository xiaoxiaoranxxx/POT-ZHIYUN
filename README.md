# 智云-一个抓取web流量的轻量级蜜罐

# 安装环境要求

apache + php7.4 + mysql8

# 系统介绍

![1722042319434](image/README/1722042319434.png)

![1722042419509](image/README/1722042419509.png)

![1722042461344](image/README/1722042461344.png)

# 宝塔搭建教程

- 创建数据库导入sql

  ![1722045130075](image/README/1722045130075.png)
- 修改.env 配置数据库密码和邮箱key

  ![1722045165942](image/README/1722045165942.png)
- 上传网站源码,配置/public为根目录

  ![1722045095547](image/README/1722045095547.png)
- 后台地址为http://127.0.0.1/xlogin/login  ,默认账号密码为admin/pot-admin

![1722045194645](image/README/1722045194645.png)

- 报错可以修改.env文件设置为APP_DEBUG为true进一步排查
